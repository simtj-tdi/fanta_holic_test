<?php

namespace App\Http\Controllers\Api\Music;

use App\Album;
use App\Artist;
use App\Http\Traits\PushTrait;
use App\Exceptions\ErrorCodes;
use App\Lib\LobbyClassv6;
use App\Lib\UserManagement;
use App\Lib\Util;
use App\Music;
use App\MvList;
use App\Lib\Response;
use App\UserScoreLog;
use Carbon\Carbon;
use App\Push;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as baseController;
use Elasticsearch\ClientBuilder;
use Illuminate\Validation\ValidationException;
use App\Lib\Log;

class Controller extends baseController
{
    use PushTrait;
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
        $this->redis = app('redis');
        $this->cache = app('cache');

    }

    // todo 2019-11-13 기준 다음 앱 배포시 삭제
    public function music_list_v2(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'type' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }
        $user = Auth('api')->user();

        $params = [
            'type' => $request->input('type', 'owner')
        ];

        $app = $request->input('app');

        if ($params['type'] == 'owner') {
            $celeb_name = app('config')['celeb'][$app]['celeb_name'];
            $artist = Artist::where('name', $celeb_name)->get()->last();
            $musics = $artist->musics()->with('album')->where('state', 1)->get();
        } else {
            $musics = Music::where('app', $app)->with('album')->where('state', 1)->where('dj_state', 1)->get();
        }

        if ($musics->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        $lobbyClass = new LobbyClassv6();
        $musics = $lobbyClass->music_parsing_v2($musics, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['musics'] = $musics;

        return $this->response->set_response(0, $result);
    }

    public function music_list_v3(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',    // krieshachu
                'type' => 'required|string',   //owner / dj
                'next' => 'required|integer',  // 페이지
                'music_id'=>'integer'          // 검색리스트에서 클릭시 사용
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();
        $type = $request->input('type', 'owner');
        $page_count =10;
        $next = $request->input('next',0);
        $app = $request->app;
        $music_id = $request->input('music_id',0);

        if( $music_id != 0){
            $musics = Music::where('id',$music_id)->get();
            $result['next'] = -1;
        }else{
            if ($type == 'owner') {
                $celeb_name = app('config')['celeb'][$app]['celeb_name'];
                $artist = Artist::where('name', $celeb_name)
                    ->get()
                    ->last();
                $musics = $artist->musics()
                    ->with('album')
                    ->where('state', 1)
                    ->orderBy('created_at','desc')
                    ->Paginate($page_count,['*'],'next');

            } else {

                $musics = Music::where('app', $app)
                    ->with('album')
                    ->where('state', 1)
                    ->where('dj_state', 1)
                    ->orderBy('created_at','desc')
                    ->Paginate($page_count,['*'],'next');
            }


            // set next
            if (!$musics->hasMorePages()) {
                $result['next'] = -1;
            } else {
                if ($next) {
                    $result['next'] = $next + 1;
                } else {
                    $result['next'] = 2;
                }
            }
        }

        if ($musics->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        $lobbyClass = new LobbyClassv6();
        $musics = $lobbyClass->music_parsing_v3($musics, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['musics'] = $musics;

        return $this->response->set_response(0, $result);
    }

    //앨범 리스트 todo 2019-11-13 이후 앱배포시 삭제
    public function album_list(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();


        $app = $request->input('app');
        $id = $request->input('id');
        $artist_name = config('celeb')[$app]['artist_name'];
        $albums = Album::with('musics')->with('artists')
            ->whereHas('musics', function ($query) use ($artist_name) {
                $query->with('artists')->wherehas('artists', function ($query) use ($artist_name) {
                    $query->where('name', $artist_name);
                });
            })
            ->where('app', $app)
            ->orderBy('order_num', 'desc');

        if (!empty($id)) {
            $albums->where('id', $id);
        }

        $albums = $albums->get();

        if ($albums->count() == 0) {
            return $this->response->set_response(-2001, null);
        }
;
        $lobbyClass = new LobbyClassv6();
        $albums = $lobbyClass->album_parsing($albums, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['albums'] = $albums;

        return $this->response->set_response(0, $result);
    }

    //앨범리스트  v2
    public function album_list_v2(Request $request){
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'next'  =>  'required|integer',
                'album_id'=>'integer'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();
        $app = $request->input('app');
        $next = $request->next;
        $album_id = $request->input('album_id',0);
        $page_count = 10;
        $artist_name = config('celeb')[$app]['artist_name'];

        if($album_id != 0 ){
            $albums = Album::where('id',$album_id)
                ->get();
            $result['next'] = -1;
        }else{
            $albums = Album::with('musics')->with('artists')
                ->whereHas('musics', function ($query) use ($artist_name) {
                    $query->with('artists')->wherehas('artists', function ($query) use ($artist_name) {
                        $query->where('name', $artist_name);
                    });
                })
                ->where('app', $app)
                ->orderBy('order_num', 'desc')
                ->Paginate($page_count,['*'],'next');

            // set next_page
            if (!$albums->hasMorePages()) {
                $result['next'] = -1;
            } else {
                if ($next) {
                    $result['next'] = $next + 1;
                } else {
                    $result['next'] = 2;
                }
            }
        }

        if ($albums->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        $lobbyClass = new LobbyClassv6();
        $albums = $lobbyClass->album_parsing($albums, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['albums'] = $albums;

        return $this->response->set_response(0, $result);
    }

    //todo 2019-11-13 이후 앱배포시 삭제
    public function music_video_list(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $params = $request->only(['app', 'id']);

        $user = Auth('api')->user();
        $artist_name = config('celeb')[$params['app']]['artist_name'];


        $music_video_boards = Music::join('boards', function ($join) use ($params) {
            $join->on('musics.mv_url', '=', 'boards.post')->where('boards.app', $params['app']);
        })
            ->with([
                'artists' => function ($query) use ($artist_name) {
                    $query->where('name', $artist_name)->select('name');
                },
            ])
            ->where('musics.app', $params['app'])
            ->where('musics.mv_url', '!=', null)
            ->select('boards.*', 'boards.id as board_id', 'musics.id'); //

        if (!empty($params['id'])) {
            $music_video_boards->where('boards.id', $params['id']);
        }
        $music_video_boards = $music_video_boards->get();

        $lobbyClass = new LobbyClassv6();
        $result['cdn_url'] = config('celeb')[$params['app']]['cdn'];
        $result['music_video_boards'] = $lobbyClass->music_vidoe_board_parsing($music_video_boards, $user);

        return $this->response->set_response(0, $result);
    }

    // 뮤직 비디오 리스트 v2
    public function music_video_list_v2(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'next' => 'required|integer',
                'music_video_board_id' => 'integer'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();
        $app = $request->app;
        $next = $request->next;
        $music_video_board_id = $request->input('music_video_board_id',0);
        $page_count = 10;
        $artist_name = config('celeb')[$app]['artist_name'];
        if($music_video_board_id != 0){
            $music_video_boards = Music::join('boards', function ($join) use ($app,$artist_name,$music_video_board_id) {
                $join->on('musics.mv_url', '=', 'boards.post')->where('boards.app', $app);
            })
                ->with([
                    'artists' => function ($query) use ($artist_name) {
                        $query->where('name', $artist_name)->select('name');
                    },
                ])
                ->where('musics.app', $app)
                ->where('boards.id',$music_video_board_id)
                ->where('musics.mv_url', '!=', null)
                ->select('boards.*', 'boards.id as board_id', 'musics.id')
                ->get();
            $result['next'] = -1;
        }else{
            $music_video_boards = Music::join('boards', function ($join) use ($app,$artist_name) {
                $join->on('musics.mv_url', '=', 'boards.post')->where('boards.app', $app);
            })
                ->with([
                    'artists' => function ($query) use ($artist_name) {
                        $query->where('name', $artist_name)->select('name');
                    },
                ])
                ->where('musics.app', $app)
                ->where('musics.mv_url', '!=', null)
                ->select('boards.*', 'boards.id as board_id', 'musics.id')
                ->orderBy('boards.created_at', 'desc')
                ->Paginate($page_count,['*'],'next');

            // set next_page
            if (!$music_video_boards->hasMorePages()) {
                $result['next'] = -1;
            } else {
                if ($next) {
                    $result['next'] = $next + 1;
                } else {
                    $result['next'] = 2;
                }
            }
        }

        if ($music_video_boards->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        $lobbyClass = new LobbyClassv6();
        $result['cdn_url'] = config('celeb')[$app]['cdn'];
        $result['music_video_boards'] = $lobbyClass->music_vidoe_board_parsing($music_video_boards, $user);

        return $this->response->set_response(0, $result);
    }

    // 뮤직 비디오 리스트 v3
    public function music_video_list_v3(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'next' => 'required|integer',
                'music_video_board_id' => 'integer'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();
        $app = $request->app;
        $next = $request->next;
        $music_video_board_id = $request->input('music_video_board_id',0);
        $page_count = 10;
        $artist_name = config('celeb')[$app]['artist_name'];

        $music_video_boards = MvList::join('boards', 'boards.id', '=', 'mv_lists.boards_id')
          ->select('boards.id as board_id','boards.thumbnail_url','boards.post','boards.thumbnail_url','boards.title','boards.video_duration','boards.app as artist_name')
          ->orderBy('boards.created_at', 'desc')
          ->Paginate($page_count,['*'],'next');

        // set next_page
        if (!$music_video_boards->hasMorePages()) {
            $result['next'] = -1;
        } else {
            if ($next) {
                $result['next'] = $next + 1;
            } else {
                $result['next'] = 2;
            }
        }


        /*if ($music_video_boards->count() == 0) {
            return $this->response->set_response(-2001, null);
        }*/

        $lobbyClass = new LobbyClassv6();
        $result['cdn_url'] = config('celeb')[$app]['cdn'];

        $result['music_video_boards'] = $lobbyClass->music_vidoe_board_parsing($music_video_boards, $user);

        return $this->response->set_response(0, $result);
    }

    //todo 2019-11-13 이후 앱배포시 삭제
    public function album_music_list(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'album_id' => 'required|integer',
                'type' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth('api')->user();

        $params = [
            'album_id' => $request->input('album_id'),
            'type' => $request->input('type', 'owner')
        ];

        $app = $request->input('app');
        $album = Album::find($params['album_id']);

        // 해당 앨범 중 셀럽 노래만
        if ($params['type'] == 'owner') {
            $celeb_name = app('config')['celeb'][$app]['celeb_name'];
            $musics = $album->musics()->with('album')->wherehas('artists', function ($query) use ($celeb_name) {
                $query->where('name', $celeb_name);
            })->where('app', $app)
                ->get();
        } else {//해당 앨범 노래 다
            $musics = $album->musics()->with('album')->where('app', $app)->get();
        }

        $lobbyClass = new LobbyClassv6();
        $musics = $lobbyClass->music_parsing_v2($musics, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['musics'] = $musics;

        return $this->response->set_response(0, $result);
    }


    public function album_music_list_v2(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'album_id' => 'required|integer',
                'type' => 'required|string',
                'next' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $user = Auth()->user();
        $page_count = 10;
        $params = [
            'album_id' => $request->input('album_id'),
            'type' => $request->input('type', 'owner'),
            'next' => $request->input('next',0)
        ];

        $app = $request->input('app');
        $album = Album::find($params['album_id']);

        // 해당 앨범 중 셀럽 노래만
        if ($params['type'] == 'owner') {
            $celeb_name = app('config')['celeb'][$app]['celeb_name'];
            $musics = $album->musics()->with('album')->wherehas('artists', function ($query) use ($celeb_name) {
                $query->where('name', $celeb_name);
            })->where('app', $app)
                ->orderBy('created_at','desc')
                ->Paginate($page_count,['*'],'next');
        } else {//해당 앨범 노래 다
            $musics = $album->musics()
                ->with('album')
                ->where('app', $app)
                ->orderBy('created_at','desc')
                ->Paginate($page_count,['*'],'next');
        }

        if ($musics->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        // set next_page
        if (!$musics->hasMorePages()) {
            $result['next'] = -1;
        } else {
            if ($params['next']) {
                $result['next'] = $params['next'] + 1;
            } else {
                $result['next'] = 2;
            }
        }

        $lobbyClass = new LobbyClassv6();
        $musics = $lobbyClass->music_parsing_v3($musics, $app, $user);

        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['musics'] = $musics;

        return $this->response->set_response(0, $result);
    }

    // 음원 상태 on/off
    public function state_update(Request $request, $music_id)
    {
        try {
            $music = Music::find($music_id);
            if ($music == null) {
                return response()->json([
                    'result' => 'fail',
                    'code' => 0,
                    'message' => 'There are no apps for such packages',
                    'data' => new \stdClass()
                ]);
            } else {
                $music->update([
                    'state' => $request->input('state')
                ]);
            }
        } catch (QueryException $e) {

            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::DATABASE_EXCEPTION,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 500);
        }

        return response()->json([
            'result' => 'success',
            'code' => 0,
            'message' => 'Success',
            'data' => new \stdClass(),
        ], 200);
    }

    public function reward(Request $request, $music_id)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $params = [
            'music_id' => $music_id
        ];
        $app = $request->input('app');
        $user = $request->user();
        $user_device = $user->devices()->get()->last();
        $util = new Util();
        //음악 검색
        $music = Music::where('app', $app)->where('id', $params['music_id'])->get()->last();
        if ($music == null) {
            return $this->response->set_response(-2001, null);
        }

        //보상 지급했는지 확인
        $reward_check = UserScoreLog::where('app',$app)
            ->where('music_id',$music->id)
//            ->where('user_id',$)
            ->where('created_at','>',Carbon::now()->addHours(-1))
            ->count();
        if($reward_check > 0){
            return $this->response->set_response(-5002,null);
        }

        //보상 지급
        $user_manage = new UserManagement();
        $user_item = $user_manage->additem($app, $user->id, $music->reward_count); //아이템 지급
        $user_manage->addscore($app,$user,5,'S',$music->id,null,null); // 포인트 지급
            //재생횟수 증가
        $music->update([
            'play_count' => $music->play_count + 1
        ]);

        // 보상알림 push 생성
        Push::create([
            'app' => $app,
            'batch_type' => 'P',
            'managed_type' => 'R',
            'user_id' => $user->id,
            'title' => $music->title . '듣기 완료!',
            'content' => '하트가 지급 되었습니다',
            'tick' => 20,
            'push_type' => 'T',
            'action' => 'A',
            'state' => 'R',
            'start_date' => Carbon::now()->addDays(-1),
        ]);

        return $this->response->set_response(0, [
            "reward_count" => $music->reward_count,
            "play_count" => $music->play_count,
            "user_item" => $user_item
        ]);
    }

}
