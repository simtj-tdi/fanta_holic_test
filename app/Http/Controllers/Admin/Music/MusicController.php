<?php

namespace App\Http\Controllers\Admin\Music;

use App\Album;
use App\Artist;
use App\Lib\Util;
use App\Music;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class MusicController extends BaseController
{
    public function index(Request $request){
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='bts';
        }
        if($app =="BTS"){
            $app = "bts";
        }
        //가수
        $artists = Artist::where('app',$app)->get()->last();

        $params = [
            'page_cnt'  =>  $request->input('page_cnt',10),
            'start_date'=>$request->input('start_date',Carbon::now()->addMonths(-7)->toDateString()),
            'end_date'=>$request->input('end_date',Carbon::tomorrow()->toDateString()),
            'state'=>$request->input('state',null),
            'search_key' => $request->input('search_key'),
            'search_value' => $request->input('search_value'),
            'sort_key' => $request->input('sort_key', 'created_at'),
            'sort_value' => $request->input('sort_value', 'desc'),
        ];


        $musics = Music::with('artists','album:id,title,thumbnail_url')->whereHas('artists',function($query) use($app,$params){
            $query->where('app',$app)->when($params['search_key'] =='artist_name',function($query) use ($params){
                $query->where('name', 'like', "%{$params['search_value']}%");
            });
        })->wherehas('album',function($query) use($params){
            $query->when($params['search_key'] == 'album_title',function($query)use($params){
                $query->where('title', 'like', "%{$params['search_value']}%");
            });
        })
            ->when($params['state'] != null,function($query) use($params){
                $query->where('state',$params['state']);
            })
            ->when($params['search_key'] == 'music_title',function($query)use($params){
                $query->where('title','like', "%{$params['search_value']}%");
            })
            ->whereBetween('created_at',[$params['start_date'],$params['end_date']])
            ->orderBy($params['sort_key'],$params['sort_value'])
            ->paginate($params['page_cnt']);

        $albums = Album::select('id','thumbnail_url','title')->where('app',$app)->get();
        $artists = Artist::select('id','name')->where('app',$app)->get();

        return view('Music.index')->with([
            'musics' => $musics,
            'albums'    =>  $albums,
            'artists'   =>  $artists,
            'params'    =>  $params,
            'search_count'  =>  count($musics),
            'total'     => Music::where('app',$app)->count(),
            'cdn_url'   =>  config('celeb')[$app]['cdn']
        ]);
    }

    //음원 저장
    public function store(Request $request){
        $util = new Util();
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'app'                        =>  $app,
            'album_id'                   =>  $request->input('album_id'),
            'album_title'                =>  $request->input('album_title'),
            'album_genre'                =>  $request->input('album_genre'),
            'album_released_at'          =>  $request->input('album_released_at'),
            'artist_id'                  =>  $request->input('artist_id'),
            'artist_name'                =>  $request->input('artist_name'),
            'repeat'                     =>  $request->input('repeat',60),
            'title'                      =>  $request->input('title'),
            'lyrics'                     =>  $request->input('lyrics'),
            'push_title'                 =>  $request->input('push_title','음악 듣기 완료'),
            'push_content'               =>  $request->input('push_content','하트가 지급 되었습니다.'),
            'push_tick'                  =>  $request->input('push_tick','음악 듣기 완료'),
            'melon_url'                  =>  $request->input('melon_url'),
            'mv_url'                     =>  $request->input('mv_url'),
            'reward_count'               =>  $request->input('reward_count'),
            'start_date'                 =>  $request->input('create_start_date'),
            'end_date'                   =>  $request->input('create_end_date')
        ];

        // 앨범 신규등록일시 앨범부터 등록
        if($params['album_id'] == 'new_album'){
            //기존 앨범 순서 조회
            $album_order_num = Album::where('app',$app)
                ->select('order_num')
                ->orderBy('order_num','desc')
                ->limit(1)
                ->get()
                ->last()->order_num;

            $album_doc = [
                'app'       => $app,
                'order_num' => $album_order_num+1,
                'title'     => $params['album_title'],
                'genre'     => $params['album_genre'],
                'released_at'=> $params['album_released_at']
            ];

            //앨범 로고 cdn 저장 및 경로저장
            if ($request->hasFile('album_thumbnail'))
            {
                $path = $app.'/images/album/logo/';
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('album_thumbnail'), $path,$app,'music');
                $album_doc['thumbnail_url']  = "/".$path.$resized_image['filename'];
            }else{
                return redirect()->back()->with(['message'  =>  'need album logo']);
            }
            $album_id = Album::create($album_doc)->id;
        }else{
            $album_id = $params['album_id'];
        }

        // 가수 신규등록일시 가수부터 등록
        if($params['artist_id'] == 'new_artist'){
            $artist_doc = [
                'app'       =>  $app,
                'name'      =>  $params['artist_name']
            ];
            $artist_id = Artist::create($artist_doc)->id;
        }else{
            $artist_id = $params['artist_id'];
        }

        //앨범 가수 관계 연결 안되있으면 연결
//        if(! Album::find($album_id)->artists()->contains($artist_id)){
//            Album::find($album_id)->artists()->attach($artist_id);
//        }

        Album::find($album_id)->artists()->syncWithoutDetaching([$artist_id]);

        // 음원 저장
        $music_doc=[
            'app'                        =>  $params['app'],
            'album_id'                   =>  $album_id,
            'repeat'                     =>  $params['repeat'],
            'title'                      =>  $params['title'],
            'lyrics'                     =>  $params['lyrics'],
            'push_title'                 =>  $params['push_title'],
            'push_content'               =>  $params['push_content'],
            'push_tick'                  =>  $params['push_tick'],
            'melon_url'                  =>  $params['melon_url'],
            'mv_url'                     =>  $params['mv_url'],
            'reward_count'               =>  $params['reward_count'],
            'start_date'                 =>  $params['start_date'],
            'end_date'                   =>  $params['end_date']
        ];
        if ($request->hasFile('thumbnail_url'))
        {
            $path = $app.'/images/music/logo/';
            $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail_url'), $path,$app,'music');
            $music_doc['thumbnail_url']  = "/".$path.$resized_image['filename'];
        }else{
            return redirect()->back()->with(['message'  =>  'need logo']);
        }

        Artist::find($artist_id)->musics()->create($music_doc);

        return redirect('admin/musics');
    }
    //수정폼이동
    public function update(Request $request,$music_id){
        $util = new Util();
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'app'                        =>  $app,
            'edit_album_id'              =>  $request->input('edit_album_id'),
            'album_genre'                =>  $request->input('edit_album_genre'),
            'album_released_at'          =>  $request->input('edit_album_released_at'),
            'edit_artist_id'             =>  $request->input('edit_artist_id'),
            'edit_artist_name'           =>  $request->input('edit_artist_name'),
            'repeat'                     =>  $request->input('repeat',60),
            'title'                      =>  $request->input('title'),
            'lyrics'                     =>  $request->input('lyrics'),
            'push_title'                 =>  $request->input('push_title','음악 듣기 완료'),
            'push_content'               =>  $request->input('push_content','하트가 지급 되었습니다.'),
            'push_tick'                  =>  $request->input('push_tick','음악 듣기 완료'),
            'melon_url'                  =>  $request->input('melon_url'),
            'mv_url'                     =>  $request->input('mv_url'),
            'reward_count'               =>  $request->input('reward_count'),
            'start_date'                 =>  $request->input('create_start_date'),
            'end_date'                   =>  $request->input('create_end_date')
        ];

        // 앨범 신규등록일시 앨범부터 등록
        if($params['edit_album_id'] == 'new_album'){
            //기존 앨범 순서 조회
            $album_order_num = Album::where('app',$app)
                ->select('order_num')
                ->orderBy('order_num','desc')
                ->limit(1)
                ->get()
                ->last()->order_num;

            $album_doc = [
                'app'       => $app,
                'order_num' => $album_order_num+1,
                'title'     => $params['edit_album_title'],
                'genre'     => $params['edit_album_genre'],
                'released_at'=> $params['edit_album_released_at']
            ];

            //앨범 로고 cdn 저장 및 경로저장
            if ($request->hasFile('edit_album_thumbnail'))
            {
                $path = $app.'/images/album/logo/';
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('edit_album_thumbnail'), $path,$app,'music');
                $album_doc['thumbnail_url']  = "/".$path.$resized_image['filename'];
            }else{
                return redirect()->back()->with(['message'  =>  'need album logo']);
            }
            $album_id = Album::create($album_doc)->id;
        }else{
            $album_id = $params['edit_album_id'];
        }

        // 가수 신규등록일시 가수부터 등록
        if($params['edit_artist_id'] == 'new_artist'){
            $artist_doc = [
                'app'       =>  $app,
                'name'      =>  $params['edit_artist_name']
            ];
            $artist_id = Artist::create($artist_doc)->id;
        }else{
            $artist_id = $params['artist_id'];
        }

        dd($request->input());

        return redirect('admin/musics');
    }
}
