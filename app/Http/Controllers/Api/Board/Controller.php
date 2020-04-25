<?php

namespace App\Http\Controllers\Api\Board;

use App\Enums\UserScoreLogType;
use App\UserItemAccumulation;
use App\Standard;
use App\Board;
use App\BoardLike;
use App\Lib\LobbyClassv6;
use App\Lib\Log;
use App\Lib\UserManagement;
use App\UserItem;
use App\UserScoreLog;
use Carbon\Carbon;
use App\Comment;
use App\Follow;
use App\Lib\Response;
use App\Lib\Util;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\UserResponseToBoard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as baseController;
use Matrix\Exception;

class Controller extends baseController
{

    protected $response;
    protected $redis;
    protected $config;
    protected $cache;
    protected $client;

    public function __construct()
    {
        $this->response = new Response();
        $this->redis = app('redis');
        $this->config = app('config')['celeb'];
        $this->cache = app('cache');
    }

    //게시물 좋아요 => 개인 보관함 등록
    public function like(Request $request)
    {
        $validator = $this->validate($request, [
            'app' => 'required',
            'board_id' => 'required',
        ]);

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'user_id' => $request->user()->id,
            'board_id' => $request->input('board_id')
        ];
        $user = $request->user();

        //저장목록인지 확인
        $check = $user->userresponsetoboard()
            ->where('user_id', $params['user_id'])
            ->where('app', $params['app'])
            ->where('board_id', $params['board_id'])
            ->where('response', 1);

        $board = Board::where('id', $params['board_id'])->get()
            ->last();

        if ($check->count() > 0) {
            $check->delete();
            $board->like = 0;
        } else {
            $user->userresponsetoboard()->create($params);
            $board->like = 1;
        }

        return $this->response->set_response(0, $board);
    }

    //게시물 좋아요
    public function board_like(Request $request)
    {
        $validator = $this->validate($request, [
            'app' => 'required',
            'board_id' => 'required',
        ]);

        //로그인한 유저인지 확인
        $user = Auth('api')->user();

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'users_id' => $user->id,
            'boards_id' => $request->input('board_id')
        ];

        $check = BoardLike::where('boards_id', $params['boards_id'])
               ->where('users_id',$params['users_id'])
               ->get();

        if ($check->count() == 0) {
            /*좋아요 추가*/
            $like = new BoardLike;
            $like->boards_id = $params['boards_id'];
            $like->users_id = $params['users_id'];
            $like->save();
        }else{
          //좋아요 취소
          BoardLike::destroy($check[0]->id);
        }
        $board = Board::where('id', $params['boards_id'])->get()
            ->last();

        $board->total_like_count = BoardLike::where('boards_id', $params['boards_id'])->count();
        $board->like = BoardLike::where('boards_id', $params['boards_id'])->where('users_id',$params['users_id'])->count();

        return $this->response->set_response(0, $board);
    }

    //게시물 좋아요 => 개인 보관함 등록 function like 와 같음 =>todo 클라에서 안쓰는 지 확인 후 삭제
//    public function celeb_like(Request $request){
//        $validator = $this->validate($request, [
//            'board_id'        => 'required',
//            'app'             => 'required'
//        ]);
//
//        $user = $request->user();
//
//        $params = [
//            'app'       =>  $request->input('app','pinxy'),
//            'user_id'   =>  $user->id,
//            'board_id'  =>  $request->input('board_id')
//        ];
//
//        //저장목록인지 확인
//        $check = $user->userresponsetoboard()
//            ->where('user_id',$params['user_id'])
//            ->where('app',$params['app'])
//            ->where('board_id',$params['board_id'])
//            ->where('response',1);
//
//        if($check->count() > 0){
//            $check->delete();
//            $is_like = 0;
//        }else {
//            $user->userresponsetoboard()->create($params);
//            $is_like = 1;
//        }
//
//        $total_like_count = Board::where('id',$params['board_id'])->get()->last()->like_count;
//
//        return $this->response->set_response(0,[
//            "total_like_count"=> $total_like_count,
//            'is_like'=>$is_like
//        ]);
//    }

    //게시물 차단 => 개인차단함 보관
    public function ban(Request $request)
    {
        $validator = $this->validate($request, [
            'board_id' => 'required',
        ]);

        $params = [
            'user_id' => $request->user()->id,
            'board_id' => $request->input('board_id'),
            'response' => 0
        ];
        $user = $request->user();

        //저장목록인지 확인
        $check = $user->userresponsetoboard()->where('user_id', $params['user_id'])->where('board_id',
            $params['board_id'])->where('response', 0);

        if ($check->count() > 0) {
            $check->delete();
            $result = false;
        } else {
            $user->userresponsetoboard()->create($params);
            $result = true;
        }
        return $this->response->set_response(0, [
            'result' => $result
        ]);
    }

    //셀럽튜브용 로비 api ->
    public function mix_list_v6(Request $request)
    {
        $user = Auth('api')->user();

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'next_token' => $request->input('next_page', 0),
        ];

//        $response = $this->redis->get("{$params['app']}:Lobby:V2:page:{$params['next_token']}");

        $lobbyClass = new LobbyClassv6();
        $response = $lobbyClass->makeCelebPage($params['app'], $params['next_token']);

        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        }
        $response['shared_url'] = config('celeb')[$params['app']]['shared_url'];
        $response['count'] = count($response['body']);
        $response['body'] = $lobbyClass->board_parsing($response['body'], $user);
        return $this->response->set_response(0, $response);
    }

    //컨텐츠 리스트
    public function get_list(Request $request, $type)
    {
        $user_id = "";
        $user = Auth('api')->user();

        if($type != "all"){
          if (!$user) {
              return $this->response->set_response(-2001, null);
          }else{
            $user_id = $user->id;
          }
        }

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'next_token' => $request->input('next_page', 0),
            'type' => $type,
            'artist_id'=> $request->input('artist_id', 0),
            'sns_type'=> $request->input('sns_type', 'all')
        ];

        $lobbyClass = new LobbyClassv6();
        $response = $lobbyClass->makeListPage($params['app'], $params['next_token'], $params['type'], $user_id ,$params['artist_id'],$params['sns_type']);

        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        }
        $response['shared_url'] = config('celeb')[$params['app']]['shared_url'];
        $response['count'] = count($response['body']);
        $response['body'] = $lobbyClass->list_parsing($response['body'], $user);

        $bn=0;
        foreach($response['body'] as $body){ // 유투브 썸네일 크롭 전 이미지 사이즈 출력
          if($body->type == "youtube"){
            $response['body'][$bn]->ori_thumbnail_h = $response['body'][$bn]->thumbnail_h + 90;
            $response['body'][$bn]->ori_thumbnail_w = $response['body'][$bn]->thumbnail_w;
          }else{
            $response['body'][$bn]->ori_thumbnail_h = $response['body'][$bn]->thumbnail_h;
            $response['body'][$bn]->ori_thumbnail_w = $response['body'][$bn]->thumbnail_w;
          }
          $bn++;
        }

        return $this->response->set_response(0, $response);
    }

    //아티스트 리스트
    public function get_list_artist(Request $request)
    {
        $user = Auth('api')->user();
        $user_id = "";

        if($user){
          $user_id = $user->id;
        }
        $params = [
            'type' => urldecode($request->input('type')),
            'app' => $request->input('app', 'fantaholic'),
            'next_token' => $request->input('next_page', 0),
            'search_query' => $request->input('search_query', ''),
        ];

        $lobbyClass = new LobbyClassv6();
        $response = $lobbyClass->makeArtistList($params['app'], $params['next_token'], $params['type'], $user_id, $params['search_query']);
        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        }

        $response['count'] = count($response['body']);
        if($response['next_page'] == '-1'){
          $response['body'][$response['count']]['id'] = -1;
          $response['body'][$response['count']]['name'] ='select';
          $response['body'][$response['count']]['team_type'] ='';
          $response['body'][$response['count']]['created_at'] ='';
          $response['body'][$response['count']]['deleted_at'] ='';
          $response['body'][$response['count']]['updated_at'] ='';
          $response['body'][$response['count']]['is_added'] =1;

        }

        return $this->response->set_response(0, $response);
    }

    //아티스트 리스트
    public function get_list_follow_artist(Request $request)
    {
        $user = Auth('api')->user();

        $lobbyClass = new LobbyClassv6();
        $response = $lobbyClass->makeArtistListFollow($user->id);
        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        }

        $response['count'] = count($response['body']);
        return $this->response->set_response(0, $response);
    }



    //단일 리스트 v6
    public function single_list_v6(Request $request, $type)
    {
        $app = $request->input('app', 'fantaholic');
        $util = new Util();

        // 1 ad_id 가 검수자인가? , 2 ip가 국내인가? 3 검수용 컨텐츠만 내보내는 중인가? app('config')['celeb']['pinxy']['inspection']
        $inspection = $util->check_inspection($request);

        //로그인한 유저인지 확인
        $user = Auth('api')->user();

        $params = [
            'gender' => (int)$request->input('gender', 3),
            'next_page' => (int)$request->input('next_page', 0),
        ];

        if ($params['next_page'] == -1) {
            return $this->response->set_response(-2001, null);
        }


        $view_ids = [];
        //봤던 게시물 안보이게 하나?
        if ($user != null) {// [1] 로그인한 유저인가?
            if ($user->repost == 0) { // [2] 봤던 게시물 안본다고 한사람인가?
                $user_view_list = UserResponseToBoard::where('response', 3)
                    ->where('user_id', $user->id)
                    ->get();
                if ($user_view_list->count() > 0) {
                    foreach ($user_view_list as $view) {
                        $view_ids[] = $view->board_id;
                    }
                }
            }
        }
        $lobbyClass = new LobbyClassv6();

        ///////////////////////////////////////////////////////////////////
        $response = $this->redis->get("SingleLobby:{$type}:{$params['gender']}:{$params['next_page']}");
        $response = null;
        if ($response == null) {
            $response = $lobbyClass->makeCelebSinglePage($app, $type, $params, $user, $view_ids, $inspection);

            $this->redis->set("SingleLobby:{$type}:{$params['gender']}:{$params['next_page']}",
                json_encode($response['body']));
            $this->redis->expire("SingleLobby:{$type}:{$params['gender']}:{$params['next_page']}", 60);
        } else {
            $response = json_decode($response, true);
        }

        $response['shared_url'] = config('celeb')[$app]['shared_url'];

        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        } else {
            $response['cdn_url'] = $this->config[$app]['cdn'];
            $response['count'] = count($response['body']);

            $response['body'] = $lobbyClass->board_parsing($response['body'], $user);

            return $this->response->set_response(0, $response);
        }
    }

    //좋아요 게시물 리스트
    public function list_like_board(Request $request)
    {
        $app = $request->input('app', 'fantaholic');

        //로그인한 유저인지 확인
        $user = Auth('api')->user();

        $params = [
            'users_id' => $user->id,
            'next_page' => (int)$request->input('next_page', 0),
        ];
        $skip = $params['next_page'] * 3;
        $board = BoardLike::where('users_id', $params['users_id'])->skip($skip)->take(3)->orderBy('created_at', 'desc')->get();

        $board = DB::table('board_likes')
            ->join('Boards', 'boards_id', '=', 'Boards.id')
            ->select('Boards.*', 'board_likes.*')
            ->get();

        $response['body'] = $board;

        //dd($response['body']);
        if (!isset($response['body']) || (isset($response['body']) && count($response['body']) == 0)) {
            return $this->response->set_response(-2001, null);
        } else {
            $response['cdn_url'] = $this->config[$app]['cdn'];
            $response['count'] = count($response['body']);

            $i=0;

            foreach($response['body'] as $body){

              $response['body'][$i]->total_like_count = BoardLike::where('boards_id', $body->boards_id)->count();
              $response['body'][$i]->like = BoardLike::where('boards_id',  $body->boards_id)->where('users_id',$params['users_id'])->count();
              $i++;

            }
            return $this->response->set_response(0, $response);
        }
    }



    //유투브 개발자 키 교체
    public function youtube_api_key(Request $request)
    {
        $params = [
            'unavailable_key' => $request->input('unavailable_key', '')
        ];

        if ($params['unavailable_key'] != '') {
            YoutubeDeveloperKey::where('key', $params['unavailable_key'])->update([
                'state' => 0
            ]);
        }

        $youtube_key = YoutubeDeveloperKey::where('state', 1)
            ->where('comment', 'app')
            ->get();


        if ($youtube_key == null) {
            return $this->response->set_response(-2001, null);
        } else {
            foreach ($youtube_key as $val) {
                $key[] = $val->key;
            }
        }

        return $this->response->set_response(0, [
            'youtube_key' => $key
        ]);
    }

    //게시물 아이템 사용
    public function item(Request $request)
    {
        $validator = $this->validate($request, [
            'app' => 'required|string',
            'board_id' => 'required',
            'item_count' => 'required|integer'
        ]);

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'board_id' => $request->input('board_id'),
            'item_count' => $request->input('item_count')
        ];
        \Illuminate\Support\Facades\Log::debug(__METHOD__ . ' - params - ' . json_encode($params));


        $user = $request->user();

        $util = new Util();
        //게시물 존재확인
        $board = Board::where('app', $params['app'])
            ->where('id', $params['board_id'])
            ->get()
            ->last();
        if ($board == null) {
            return $this->response->set_response(-2001, null);
        }

        //유저 아이템 개수 확인
        if ($user->item_count < $params['item_count']) {
            return $this->response->set_response(-5001, null);
        }

        //유저 아이템 개수 소모
        $user->item_count -= $params['item_count'];
        $user->save();
        $board->update([
            'item_count' => $board->item_count + $params['item_count']
        ]);

        // 아이템/포인트 기준
        $item_point_standard = Standard::select('item_point_count')
            ->where('app', $params['app'])
            ->get()->last()->item_point_count;

        //누적 유저 아이템 사용 개수
        $used_user_item_count = UserItemAccumulation::where('app', $params['app'])
            ->where('user_id', $user->id)
            ->get()->last();

        //누적사용개수 기록이없는경우 생성
        if ($used_user_item_count == null) {
            UserItemAccumulation::create([
                'app' => $params['app'],
                'user_id' => $user->id,
                'item_count' => 0
            ]);
            $used_user_item_count = 0;
        } else {
            $used_user_item_count = $used_user_item_count->item_count;
        }

        //누적 아이템사용수 + 소모한 아이템 개수가 포인트획득 기준보다 클경우 점수지급하고 누적사용수 초기화
        if ($used_user_item_count + $params['item_count'] > $item_point_standard) {
            //지급할 점수 누적아이템사용수 / 기준
            $point = (int)(($used_user_item_count + $params['item_count']) / $item_point_standard);
            //점수 지급
            $user_manage = new UserManagement();
            $user_manage->addscore($params['app'], $user, $point, 'I', null, null, $params['board_id']);
        }

        //누적 사용수 초기화 => 누적아이템사용수 % 기준
        UserItemAccumulation::where('app', $params['app'])
            ->where('user_id', $user->id)->update([
                'item_count' => ($used_user_item_count + $params['item_count']) % $item_point_standard
            ]);

        //유저 아이템 사용 내역 기록
        UserItem::create([
            'app' => $params['app'],
            'user_id' => $user->id,
            'item_count' => $params['item_count'],
            'board_id' => $params['board_id'],
            'log_type' => 'B',
            'description' => '게시물 아이템 사용'
        ]);

        $result = [
            'board_item_total' => $board->item_count,
            'user_item_count' => $user->item_count,
        ];

        return $this->response->set_response(0, $result);
    }

    //베스트 게시물 10개 (아이템 기준)
    public function best_list(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'type' => 'required'                           // artist,fanfeed
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

        $app = $request->input('app');
        $user = Auth('api')->user();
        $type = $request->input('type');
        $cache_min = 60; // 캐시 저장 시간 (분)
        $list_limit = 168; // 24 * 7 => 일주일이상 노출된 게시물은 리스트에서 제외

        // 베스트 게시물 캐시처리 1시간씩 , + 베스트 게시물 특정 횟수 이상 노출시 이후 리스트에서 제외
        $best_ids = $this->cache->remember("{$app}:{$type}:best_list", $cache_min * 60,
            function () use ($app, $list_limit, $type) {
                $best_ids = Board::select('id')
                    ->where('app', $app)
                    ->when($type == 'artist', function ($query) {
                        $query->where('type', '!=', 'fanfeed');
                    })
                    ->when($type == 'fanfeed', function ($query) {
                        $query->where('type', 'fanfeed');
                    })
                    ->where('state', 1)
                    ->where('best_list_cnt', '<', $list_limit)
                    ->orderBy('item_count', 'desc')
                    ->orderBy('created_at', 'asc')
                    ->limit(10)
                    ->get()->map(function ($val) {
                        return $val->id;
                    })->toArray();

                //베스트 게시물 노출횟수 +1
                Board::whereIn('id', $best_ids)->update([
                    'best_list_cnt' => DB::raw('best_list_cnt+1')
                ]);
                return $best_ids;
            });

        //게시물 다 제외해서 없을경우 예외처리 => 노출 횟수 상관없이 1.아이템 개수 desc => 2.등록시간 asc 순서로 출력
        if (count($best_ids) == 0) {
            $best_ids = Board::select('id')
                ->where('app', $app)
                ->when($type == 'artist', function ($query) {
                    $query->where('type', '!=', 'fanfeed');
                })
                ->when($type == 'fanfeed', function ($query) {
                    $query->where('type', 'fanfeed');
                })
                ->where('state', 1)
                ->orderBy('item_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(10)
                ->get()->map(function ($val) {
                    return $val->id;
                })->toArray();

            //그래도 없으면 게시물 없음 에러코드
            if (count($best_ids) == 0) {
                return $this->response->set_response(-2001, null);
            }
        }

        $best_ids_string = implode("','", $best_ids);
        $bests = Board::whereIn('id', $best_ids)
            ->orderByRaw(DB::raw("FIELD(id,'$best_ids_string')"))
            ->get();



        $lobbyclass = new LobbyClassv6();
        $bests = $lobbyclass->board_parsing($bests, $user);

        $result['cdn_url'] = config('celeb')[$app]['cdn'];
        $result['best'] = $bests;

        return $this->response->set_response(0, $result);
    }

    public function artist_best(Request $request)
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

        $app = $request->input('app');
        $user = Auth('api')->user();
        $cache_min = 60; // 캐시 저장 시간 (분)
        $list_limit = 168; // 24 * 7 => 일주일이상 노출된 게시물은 리스트에서 제외

        // 베스트 게시물 캐시처리 1시간씩 , + 베스트 게시물 특정 횟수 이상 노출시 이후 리스트에서 제외
        $artist_best_ids = $this->cache->remember("{$app}:artist_best_list", $cache_min * 60,
            function () use ($app, $list_limit) {
                $artist_best_ids = Board::select('id')
                    ->where('app', $app)
                    ->where('type', '!=', 'fanfeed')
                    ->where('state', 1)
                    ->where('best_list_cnt', '<', $list_limit)
                    ->orderBy('item_count', 'desc')
                    ->orderBy('created_at', 'asc')
                    ->limit(10)
                    ->get()->map(function ($val) {
                        return $val->id;
                    })->toArray();

                //베스트 게시물 노출횟수 +1
                Board::whereIn('id', $artist_best_ids)->update([
                    'best_list_cnt' => DB::raw('best_list_cnt+1')
                ]);
                return $artist_best_ids;
            });

        //게시물 다 제외해서 없을경우 예외처리 => 노출 횟수 상관없이 1.아이템 개수 desc => 2.등록시간 asc 순서로 출력
        if (count($artist_best_ids) == 0) {
            $artist_best_ids = Board::select('id')
                ->where('app', $app)
                ->where('type', '!=', 'fanfeed')
                ->where('state', 1)
                ->orderBy('item_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(10)
                ->get()->map(function ($val) {
                    return $val->id;
                })->toArray();

            //그래도 없으면 게시물 없음 에러코드
            if (count($artist_best_ids) == 0) {
                return $this->response->set_response(-2001, null);
            }
        }

        $artist_best_ids_string = implode("','", $artist_best_ids);
        $artist_bests = Board::whereIn('id', $artist_best_ids)
            ->orderByRaw(DB::raw("FIELD(id,'$artist_best_ids_string')"))
            ->get();

        $lobbyclass = new LobbyClassv6();
        $artist_bests = $lobbyclass->board_parsing($artist_bests, $user);

        $result['cdn_url'] = config('celeb')[$app]['cdn'];
        $result['artists_best'] = $artist_bests;

        return $this->response->set_response(0, $result);
    }

    public function fanfeed_best(Request $request)
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

        $app = $request->input('app');
        $user = Auth('api')->user();
        $cache_min = 60; // 캐시 저장 시간 (분)
        $list_limit = 168; // 24 * 7 => 일주일이상 노출된 게시물은 리스트에서 제외

        // 베스트 게시물 캐시처리 1시간씩 , + 베스트 게시물 특정 횟수 이상 노출시 이후 리스트에서 제외
        $best_feed_ids = $this->cache->remember("{$app}:best_fanfeed_list", $cache_min * 60,
            function () use ($app, $list_limit) {
                $best_feed_ids = Board::select('id')
                    ->where('app', $app)
                    ->where('type', 'fanfeed')
                    ->where('state', 1)
                    ->where('best_list_cnt', '<', $list_limit)
                    ->orderBy('item_count', 'desc')
                    ->orderBy('created_at', 'asc')
                    ->limit(10)
                    ->get()->map(function ($val) {
                        return $val->id;
                    })->toArray();

                //베스트 게시물 노출횟수 +1
                Board::whereIn('id', $best_feed_ids)->update([
                    'best_list_cnt' => DB::raw('best_list_cnt+1')
                ]);

                return $best_feed_ids;
            });

        //게시물 다 제외해서 없을경우 예외처리 => 노출 횟수 상관없이 1.아이템 개수 desc => 2.등록시간 asc 순서로 출력
        if (count($best_feed_ids) == 0) {
            $best_feed_ids = Board::select('id')
                ->where('app', $app)
                ->where('type', 'fanfeed')
                ->where('state', 1)
                ->orderBy('item_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(10)
                ->get()->map(function ($val) {
                    return $val->id;
                })->toArray();

            //그래도 없으면 게시물 없음 에러코드
            if (count($best_feed_ids) == 0) {
                return $this->response->set_response(-2001, null);
            }
        }

        $best_feed_ids_string = implode("','", $best_feed_ids);
        $best_feeds = Board::whereIn('id', $best_feed_ids)
            ->orderByRaw(DB::raw("FIELD(id,'$best_feed_ids_string')"))
            ->get();

        $lobbyclass = new LobbyClassv6();
        $best_feeds = $lobbyclass->board_parsing($best_feeds, $user);

        $result['cdn_url'] = config('celeb')[$app]['cdn'];
        $result['fanfeed_best'] = $best_feeds;

        return $this->response->set_response(0, $result);
    }

    public function fanfeed_upload(Request $request)
    {
        // Get Request
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'title' => 'required|string',
                'content' => 'required|string',
                'hashtag' => 'string',
                'thumbnail' => 'required|image',
                'files' => 'required'
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
            'app' => $request->input('app'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'ori_tag' => (!empty($request->input('hashtag'))) ? explode(',', $request->input('hashtag')) : array(),
            'deleted' => 0,
            'state' => 0,
            'created_date' => Carbon::now()
        ];
        // UtilClass Definition
        $util = new Util();

        $user = $request->user();
        //todo 블랙리스트 유저 or 업로드 조건 기획 없는대 생기면 조건검사후 미자격시 에러코드

        $fanfeed_data['app'] = $params['app'];
        $fanfeed_data['type'] = 'fanfeed';
        $fanfeed_data['title'] = $params['title'];
        $fanfeed_data['contents'] = $params['content'];
        $fanfeed_data['user_id'] = $user->id;

        try {
            //thumnail 파일 cdn 저장
            if ($request->hasFile('thumbnail')) {
                $path = 'images/fanfeed/thumbnail/';
                $thumbnail = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail'), $path, $params['app'],
                    'fanfeed');
                $fanfeed_data['thumbnail_url'] = "/" . $path . $thumbnail['filename'];
                $fanfeed_data['thumbnail_w'] = (int)$thumbnail['width'];
                $fanfeed_data['thumbnail_h'] = (int)$thumbnail['height'];
                $fanfeed_data['sns_account'] = $user->nickname;
                $fanfeed_data['recorded_at'] = Carbon::now();
                $fanfeed_data['ori_tag'] = $params['ori_tag'];
            }

            //파일 저장
            if ($request->hasFile('files')) {
                // get file from input data
                $files = $request->File('files');
                foreach ($files as $file) {
                    // get file type
                    $file_type = $file->getMimeType();
                    if (strstr($file_type, "video/")) {
                        $uploads[] = [
                            'type' => 'video',
                            'video_path' => 'video/fanfeed/src/',
                            'poster_path' => 'video/fanfeed/poster/',
                            'file' => $file
                        ];
                        $fanfeed_data['post_type'] = 'video';
                    } else {
                        if (strstr($file_type, "image/")) {
                            $uploads[] = [
                                'type' => 'image',
                                'path' => 'images/fanfeed/src/',
                                'file' => $file
                            ];
                            $fanfeed_data['post_type'] = 'img';
                        } else {
                            return $this->response->set_response(-1001, null);
                        }
                    }
                }

                if (count($files) > 1) {
                    $fanfeed_data['post_type'] = 'post';
                }

                foreach ($uploads as $upload) {
                    if ($upload['type'] == 'video') {
                        $video_filename = $util->SaveFileAzure($upload['file'], $upload['video_path']);
                        $poster_filename = $util->SaveVideoPoster($upload['file'], $upload['poster_path'], $user);
                        $fanfeed_data['data'][] = [
                            $upload['type'] => [
                                'src' => "/" . $upload['video_path'] . $video_filename,
                                'poster' => "/" . $upload['poster_path'] . $poster_filename,
                            ],
                        ];
                    } elseif ($upload['type'] == 'image') {
                        $filename = $util->SaveFileAzure($upload['file'], $upload['path']);

                        $fanfeed_data['data'][] = [
                            $upload['type'] => "/" . $upload['path'] . $filename
                        ];
                    }
                }
                $fanfeed_data['data'] = $fanfeed_data['data'];
            }
            $fandfeed_id = Board::create($fanfeed_data)->id;

            $userScore = new UserScoreLog();
            $userScore->app = $params['app'];
            $userScore->type = UserScoreLogType::REGISTER_BOARD();
            $userScore->board_id = $fandfeed_id;
            $userScore->user_id = $user->id;
            $userScore->score = UserScoreLog::FAN_FEED_BOARD_REGISTER_SCORE;
            $userScore->save();

        } catch (Exception $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -2007,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        return $this->response->set_response(0, null);
    }
    //뉴스 게시글 업로드 기능 -> 관리자 테스트 시 임시로 사용 20200114
    public function upload_news(Request $request)
    {
        $params = [
            'app' => $request->input('app'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'ori_tag' => (!empty($request->input('hashtag'))) ? explode(',', $request->input('hashtag')) : array(),
            'deleted' => 0,
            'state' => 0,
            'post' => $request->input('post'),
            'created_date' => Carbon::now()
        ];
        // UtilClass Definition
        $util = new Util();

        $news_data['app'] = $params['app'];
        $news_data['type'] = 'news';
        $news_data['title'] = $params['title'];
        $news_data['contents'] = $params['content'];
        $news_data['user_id'] = $request->input('user_id');
        $news_data['post'] = $params['post'];

        try {
            //thumnail 파일 cdn 저장
            if ($request->hasFile('thumbnail')) {
                $path = 'images/news/thumbnail/';
                $thumbnail = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail'), $path, $params['app'],
                    'news');
                $news_data['thumbnail_url'] = "/" . $path . $thumbnail['filename'];
                $news_data['thumbnail_w'] = (int)$thumbnail['width'];
                $news_data['thumbnail_h'] = (int)$thumbnail['height'];
                $news_data['sns_account'] = '';
                $news_data['recorded_at'] = Carbon::now();
                $news_data['ori_tag'] = $params['ori_tag'];
            }

            $news_id = Board::create($news_data)->id;

        } catch (Exception $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -2007,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        return $this->response->set_response(0, null);
    }

    //게시물 새로고침
    public function refresh(Request $request, $board_id)
    {
        $params = [
            'app' => $request->input('app', 'fantaholic'),
        ];
        $user = Auth('api')->user();
        if ($user != null && UserResponseToBoard::where('app', $params['app'])->where('board_id',
                $board_id)->where('user_id', $user->id)->where('response', 1)->count() != 0) {
            $board['is_like'] = 1;
        } else {
            $board['is_like'] = 0;
        }
        //유저 게시물 아이템 사용 개수
        if ($user != null) {
            $board['user_item_count'] = UserItem::where('board_id', $board_id)->where('user_id',
                $user->id)->sum('item_count');
        } else {
            $board['user_item_count'] = 0;
        }

        //해당게시물 좋아요 개수 확인
//        $board['total_like_count'] = Board::where('id', $board_id)->get()->last()->like_count;
        $board['total_item_count'] = Board::where('id', $board_id)->get()->last()->item_count;
        $board['comment_count'] = Comment::where('board_id', $board_id)->count();
//        $board['created_at_timestamp'] =  (string)(Carbon::createFromTimeString($board['created_at'])->timestamp);
        return $this->response->set_response(0, $board);
    }

    //게시물 새로고침 v2
    public function refresh_v2(Request $request, $board_id)
    {
        $params = [
            'app' => "fantaholic",
        ];

        $user = Auth('api')->user();
        \Illuminate\Support\Facades\Log::debug(__METHOD__ . ' - user id - ' . json_encode($user));

        $board = Board::where('id', $board_id)->get()->last();

//        //유저 게시물 아이템 사용 개수
//        if ($user != null) {
//            $board_state['user_item_count'] = UserItem::where('board_id', $board_id)->where('user_id',
//                $user->id)->sum('item_count');
//        } else {
//            $board_state['user_item_count'] = 0;
//        }

        //해당게시물 좋아요 개수 확인
        //$board['item_count'] = Board::where('id', $board_id)->get()->last()->item_count;
        $board['comment_count'] = Comment::where('board_id', $board_id)->get()->count();

        $result['cdn_url'] = config('celeb')[$params['app']]['cdn'];
        $result['shared_url'] = config('celeb')[$params['app']]['shared_url'];
        $lobbyClass = new LobbyClassv6();
        $result['board'] = $lobbyClass->board_parsing([$board], $user)[0];

        if($user != null){
            $result['board']->like = BoardLike::where('boards_id', $board_id)->where('users_id',$user->id)->get()->count();
        }else{
            $result['board']->like = 0;
        }

        $result['board']->total_like_count = BoardLike::where('boards_id', $board_id)->get()->count();


        if($result['board']->type == "instagram" ){// vlive x-path추가, 크롤링해서 데이터 리턴

          $contents_info = array();

          $i =0;
          foreach($result['board']->data as $data){
            if (property_exists($data, 'image')){
              $contents_info[$i]['contents_type'] = 'image';
              $contents_info[$i]['xpath'] = config('xpath')[$result['board']->type]['img']['xpath'];

            }elseif(property_exists($data, 'video')){
              $contents_info[$i]['contents_type'] = 'vod';
              $contents_info[$i]['xpath'] = config('xpath')[$result['board']->type]['vod']['xpath'];
              $contents_info[$i]['thumbnail_xpath'] = config('xpath')[$result['board']->type]['img']['xpath'];
            }
            $i++;
          }


          $result['board']->contents_info = $contents_info ;
          $result['board']->xpath_ver = config('xpath')[$result['board']->type]['version'];
          //dd($oriData);

        }


        if( $result['board']->type == "vlive"){ // vlive x-path추가

          $contents_info = array();
          $i = 0;

          foreach($result['board']->data as $data){
            if (property_exists($data, 'image')){
              $contents_info[$i]['contents_type'] = 'image';
              $contents_info[$i]['xpath'] = config('xpath')[$result['board']->type]['img']['xpath'];

            }elseif(property_exists($data, 'video')){
              $contents_info[$i]['contents_type'] = 'vod';
              $contents_info[$i]['xpath'] = config('xpath')[$result['board']->type]['vod']['xpath'];
            }
            $i++;
          }

          $result['board']->contents_info = $contents_info ;
          $result['board']->xpath_ver = config('xpath')[$result['board']->type]['version'];

        }

        if($result['board']->type == "twitter"){// twitter x-path추가
          $result['board']->url = config('xpath')[$result['board']->type]['url'].$result['board']->sns_account."/status/".$result['board']->post;
        }else{
          $result['board']->url = config('xpath')[$result['board']->type]['url'].$result['board']->post;
        }

        return $this->response->set_response(0, $result);

    }

    public function board_info(Request $request, $board_id)
    {
        $params = [
            'app' => $request->input('app', 'fantaholic'),
        ];
        $user = Auth('api')->user();

        $board = Board::where('id', $board_id)
            ->get();

        $lobbyclass = new LobbyClassv6();
        $board = $lobbyclass->board_parsing($board, $user);
        $result['cdn_url'] = config('celeb')[$params['app']]['cdn'];
        $result['shared_url'] = config('celeb')[$params['app']]['shared_url'];
        $result['board'] = $board[0];

        return $this->response->set_response(0, $result);
    }

    public function delete_board(Request $request, $board_id)
    {

      if($board_id == null) {
          return $this->response->set_response(-1001,"board id is null");
      }

      $board = Board::where('id', $board_id)->update(["state" => 0]);

      return $this->response->set_response(0,null);
    }

    public function update_thumbnail(Request $request, $board_id)
    {

      $ori_thumbnail = $request->input('ori_thumbnail');
      if($board_id == null) {
          return $this->response->set_response(-1001,"board id is null");
      }

      if($request->input('ori_thumbnail') == null) {
          return $this->response->set_response(-1001,"ori_thumbnail id is null");
      }

      $board = Board::where('id', $board_id)->update(["ori_thumbnail" => $ori_thumbnail]);

      return $this->response->set_response(0,null);
    }




}
