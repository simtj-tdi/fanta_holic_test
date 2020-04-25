<?php

namespace App\Http\Controllers\Admin\Board;

use App\BanedWord;
use App\Board;
use App\Lib\Channel\News;
use App\Tag;
use App\UpdateLog;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\ImageSource;
use Illuminate\Support\Facades\Session;
use App\Lib\Util;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Elasticsearch\ClientBuilder;



class BoardController extends BaseController
{
    protected $logs;
    protected $util;
    public function __construct(UpdateLog $logs)
    {
        $this->util = new Util();
        $this->logs = $logs;
    }

    public function index(Request $request){

        //test
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'board_id'  =>  $request->input('board_id',null),
            'type'  =>  $request->input('type','youtube'),
            'start_date' => $request->input('start_date','2019-01-15'),
            'end_date' => $request->input('end_date',Carbon::now()->toDateString()),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'text_check'    =>  $request->input('text_check',3),
            'search'    =>  $request->input('search')
        ];

        // 영어 숫자 => true    그외 false
        $params['is_eng_or_num'] = ctype_alnum($params['tags']);

        $starttime = Carbon::createFromTimeString($params['start_date'].' 00:00:00');
        $endtime = Carbon::createFromTimeString($params['end_date'].' 23:59:59');
        $boards = Board::where('app',$app)
            ->when($params['type']!=null,function ($query) use($params){
                return $query->where('type',$params['type']);
            })
            ->when($params['text_check']!=3,function($query) use ($params){
                return $query->where('text_check',$params['text_check']);
            })
            ->when($params['state']!=null,function($query) use ($params){
                return $query->where('state',(int)$params['state']);
            })
            ->when($params['gender']!=null,function($query) use ($params){
                return $query->where('gender',(int)$params['gender']);
            })
            ->whereBetween('created_at', [$starttime, $endtime])
            ->when($params['tags'],function ($query) use ($params) {
                if($params['is_eng_or_num']){
                    //영어 숫자 일경우 3글자부터 중복체크
                    if(mb_strlen($params['tags'])>2){
                        return $query->where(function($query) use ($params) {
                            $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                                ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                        });
                    }else{
                        return $query->where(function($query) use ($params){
                            $query->where('ori_tag','like','%"'.$params['tags'].'"%')
                                ->orwhere('custom_tag','like','%"'.$params['tags'].'"%');
                        });
                    }
                }else{
                    //한글,일본어,한자 2글자부터 중복체크
                    if(mb_strlen($params['tags'])>1){
                        return $query->where(function($query) use ($params) {
                            $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                                ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                        });
                    }else{
                        return $query->where(function($query) use ($params){
                            $query->where('ori_tag','like','%"'.$params['tags'].'"%')
                                ->orwhere('custom_tag','like','%"'.$params['tags'].'"%');
                        });
                    }
                }
            })
            ->when($params['state'],function ($query) use ($params) {
                return $query->where('state',$params['state']);
            })
            ->when($params['search'],function($query) use ($params){
                return $query->where('search',$params['search']);
            });
        $total_cnt = $boards->count();

        $page_cnt = 50;

        $rows = $boards->orderBy('created_at','desc')
            ->Paginate($page_cnt);


        {{ $rows->appends($params)->links(); }}

        if (count($rows) > 0) {
            $params['last'] = $rows[count($rows)-1]->created_at;
        }
        else
            $params['last'] = '';

        //2019.2.8 cch 태그검색자동완성
        $tags = Tag::select('name')->limit(10000)
            ->get();

        foreach($tags as $tag){
            $tag_names[] = $tag->name;
        }
        if(isset($tag_names)){
        $tags =json_encode(array_values(array_unique($tag_names)));
        }else{
            $tags = json_encode([]);
        }
        //@end 2019.2.8 cch 태그검색자동완성

        return view('Boards.index')->with([
            $params['type'].'_menu' => 'active',
            'params' => $params,
            'tag_list'  =>  $tags,
            'rows' => $rows,
            'total' => $total_cnt
        ]);
    }

    public function index2(Request $request){
        $logs = $this->logs->latest('created_at')->paginate(20,['*'],'log_page');
        if ($request->ajax()) {
            return view('layouts.asidemenu', ['logs' => $logs])->render();
        }
        $user= $request->user();

        if($user != null){
            $app = $request->user()->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'board_id'  =>  $request->input('board_id',null),
            'type'  =>  $request->input('type'),
            'start_date' => $request->input('start_date',Carbon::now()->addDays(-7)->toDateString()),
            'end_date' => $request->input('end_date',Carbon::now()->toDateString()),
            'state' => $request->input('state'),
            'tags' => $request->input('tags'),
            'text_check'    =>  $request->input('text_check',3),
            'face_check'    =>  $request->input('face_check','all'),
            'app_review'    =>  $request->input('app_review',null),
            'search'    =>  $request->input('search')
        ];
        //dd($params);
        // 영어 숫자 => true    그외 false
        $params['is_eng_or_num'] = ctype_alnum($params['tags']);

//        if($params['board_id'] != null){
//            $board = Board::withTrashed()->find($params['board_id']);
//            $params['start_date'] = Carbon::createFromTimeString($board->created_at)->toDateString();
//            $params['end_date'] = Carbon::createFromTimeString($board->created_at)->toDateString();
//        }

        $starttime = Carbon::createFromTimeString($params['start_date'].' 00:00:00');
        $endtime = Carbon::createFromTimeString($params['end_date'].' 23:59:59');

        $boards = Board::where('app',$app)
            ->when($params['type']!=null,function ($query) use($params){
                return $query->where('type',$params['type']);
            })
//            ->when($params['type']=='youtube',function($query) use($params){
//                return $query->whereIn('search',["UCn8pxWEV8SfTB5D81ykj1Xw", "UCxo0l6nzsaQRmubjsWna8kg", "UCHi5ZQWu2oplq8coKwLygNQ", "UCQjz2a-gWxbtGoSmeaioh3w", "UCa_rR3kBarKWS9KSp8BtoHQ"]);
//            })
            ->when($params['text_check']!=3,function($query) use ($params){
                return $query->where('text_check',$params['text_check']);
            })
            ->when($params['face_check']!='all',function($query) use($params){
                if($params['face_check']==0){
                    return $query->where('face_check',$params['face_check']);
                } else{
                    return $query->where('face_check','>',0);
                }
            })
            ->when($params['app_review']!=null,function($query) use ($params){
                return $query->where('app_review',$params['app_review']);
            })
            ->when($params['state']!=null,function($query) use ($params){
                return $query->where('state',(int)$params['state']);
            })
//            ->when($params['gender']!=null,function($query) use ($params){
////                return $query ->where('gender',$params['gender']);
////            })
            ->whereBetween('created_at', [$starttime, $endtime])
//            ->when($params['board_id'] != null,function($query) use($params){
//                return $query->withTrashed()->where('id',$params['board_id']);
//            })
            ->when($params['tags'],function ($query) use ($params) {
                if($params['is_eng_or_num']){
                    //영어 숫자 일경우 3글자부터 중복체크
                    if(mb_strlen($params['tags'])>2){
                        return $query->where(function($query) use ($params) {
                            $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                                ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                        });
                    }else{
                        return $query->where(function($query) use ($params){
                            $query->where('ori_tag','like','%"'.$params['tags'].'"%')
                                ->orwhere('custom_tag','like','%"'.$params['tags'].'"%');
                        });
                    }
                }else{
                    //한글,일본어,한자 2글자부터 중복체크
                    if(mb_strlen($params['tags'])>1){
                        return $query->where(function($query) use ($params) {
                            $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                                ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                        });
                    }else{
                        return $query->where(function($query) use ($params){
                            $query->where('ori_tag','like','%"'.$params['tags'].'"%')
                                ->orwhere('custom_tag','like','%"'.$params['tags'].'"%');
                        });
                    }
                }
            })
            ->when($params['state'],function ($query) use ($params) {
                return $query->where('state',$params['state']);
            })
            ->when($params['search'],function($query) use ($params){
                return $query->where('search',$params['search']);
            });

        $total_cnt = $boards->count();

        $page_cnt = 50;

        $rows = $boards->orderBy('recorded_at','desc')
            ->Paginate($page_cnt);

        foreach($rows as $row){
            if($row->state == 0 && $row->recorded_at > Carbon::now()->addHours(-25)){
                $row->new = true;
            }
        }
        {{ $rows->appends($params)->links(); }}

        //2019.2.8 cch 태그검색자동완성
        $tags = Tag::select('name')->limit(10000)
            ->get();

        foreach($tags as $tag){
            $tag_names[] = $tag->name;
        }
        if(isset($tag_names)){
        $tags =json_encode(array_values(array_unique($tag_names)));
        }else{
            $tags = json_encode([]);
        }
        //@end 2019.2.8 cch 태그검색자동완성

        return view('Boards.index2')->with([
            'total_menu' => 'active',
            'params' => $params,
            'tag_list'  =>  $tags,
            'rows' => $rows,
            'total' => $total_cnt,
            'logs'  =>  $logs
        ]);
    }

    public function store(Request $request){
        $validator = $this->validate($request,[
            'thumbnail'    =>  'required',
            'url'           =>  'required',
            'title'         =>  'required',
        ]);
        $params = [
            'type'          =>  $request->input('type'),
            'post'          =>  $request->input('url'),
            'data'          =>  $request->input('data'),
            'sns_account'   =>  $request->input('sns_account'),
            'ori_tag'       =>  $request->input('create_tag'),
            'title'         =>  $request->input('title'),
            'state'         =>  0,
            'gender'        =>  1,
            'contents'      =>  $request->input('contents'),
            'created_at'  =>  $request->input('created_at',Carbon::now('Asia/Seoul')->toDateTimeString()),
        ];

        $user = $request->user();
        if($user != null){
            $params['app'] = $user->app;
        }else{
            $params['app'] = 'fantaholic';
        }

        //중복 게시물이 있을경우 알람 뛰우면서 해당게시물 리턴
        // 삭제된 게시물중에 중복이 있을 경우 복구하고 알람
        $duplecheck =Board::withTrashed()->where('type',$params['type'])->where('post',$params['post'])->get();
        if(count($duplecheck)>0){
            if($duplecheck[0]->deleted_at == null){
                Session::flash('message', "이미 있는 url 입니다");
                return view('Boards.form')->with([
                    'params' => $params,
                    $params['type'].'_menu' => 'active',
                    'id' => $duplecheck[0]->id,
                    'rows' => $duplecheck
                ]);
            }else{
                Session::flash('message', "삭제된 게시물중에 이미 있는 url 입니다");
                return view("Boards.form")->with([
                    'params' => $params,
                    $params['type'].'_menu' => 'active',
                    'id' => $duplecheck[0]->id,
                    'rows' => $duplecheck
                ]);
            }
        }

        $params['created_at'] = Carbon::createFromTimeString($params['created_at']);
        $ori_tags = $collection = collect(explode(',', $params['ori_tag']))->toArray();
        if($params['ori_tag'] != null){
            foreach($ori_tags as $ori_tag){
                DB::insert('insert ignore into tags (name,board,type) values (?,?,?)',[$ori_tag,$params['type'],'custom']);
            }
        }
        //2019.01.29 cch 태그 중복 제거
        $ori_tag = explode(',',implode(',',array_unique($ori_tags)));

        $json_tag = null;
        if(!empty($oriTag)){
            $json_tag = json_encode($ori_tag);
        }

        $document = [
            'type'          =>  $params['type'],
            "app"           =>  $params['app'],
            'title'         =>  $params['title'],
            'post'           =>  $params['post'],
            'data'           =>  $params['data'],
            'sns_account'   =>  $params['sns_account'],
            "ori_tag"          => $json_tag,
            "created_at"  =>  $params['created_at'],
            'gender'        =>  $params['gender'],
            "state"         =>  (int)$params['state'],
            'contents'      =>  $params['contents'],
            'recorded_at'   =>  Carbon::now()
        ];


        // file save
        $util = new Util();
        $path = $params['app'].'/images/'.$params['type'].'/thumbnail/';

        if($request->hasFile('thumbnail'))
        {
            $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail'), $path,$params['app'],$params['type']);
            $document['thumbnail_url'] = "/".$path.$resized_image['filename'];
            $document['thumbnail_w'] = (int) $resized_image['width'];
            $document['thumbnail_h'] =  (int) $resized_image['height'];
        }

        $path = $params['app'].'/file/'.$params['type'].'/src/';
        if($request->hasFile('content_files')){
            $data = [];
            foreach($request->file('content_files') as $file){
                dd($request->file('content_files'), $file);
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($file, $path,$params['app'],$params['type']);
                $image_save = new \stdClass();
                $image_save->image = "/".$path.$resized_image['filename'];
                $data[] = $image_save;
            }
            $document['data'] = '[{"image":"'.$image_save->image.'"}]';
            //dd($document['data']);
        }else{ //데이터가 없으면 썸네일로 채워둠
            $data = array([
                'image' => $document['thumbnail_url']
            ]);
            $document['data'] = $data;
            //$document['data'] = json_encode($data);
            //dd($data);
//            $document['data'] = json_encode($data);
//            $document['data'] = $data;
//            $document['data'] = json_encode($data, JSON_UNESCAPED_SLASHES);
//            $document['data'] = str_replace('\\/', '/', json_encode($data));
//            $document['data'] = json_encode($data, JSON_FORCE_OBJECT);
//            $document['data'] = json_encode($data, JSON_PRETTY_PRINT);
//            $document['data'] = json_encode($data, JSON_UNESCAPED_SLASHES);
//            dd($data);



        }
        //dd($document['data']);
        // Insert DB
        $board = Board::create($document);
        UpdateLog::create([
            'board_id'  =>  $board->id,
            'board_type' => $board->type,
            'update_name'   =>  'create',
        ]);

        return redirect()->back()->with([
           'params' =>  $params
        ]);
    }

    //게시물 수정 화면
    public function edit(Request $request,$board_id){
        $params = [
            'type'  =>  $request->input('type')
        ];
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $rows = null;

        if ($board_id != '0') {
            $item_count = 1;
            $rows = Board::where('app',$app)
                ->withTrashed()
                ->when($params['type'] != null,function($query) use($params){
                    return $query->where('type',$params['type']);
                })
                ->where('id',$board_id)
                ->limit($item_count)->get();
        }

        $oriTag = $rows[0]->ori_tag;
        if(!empty($oriTag)){
            $rows[0]->ori_tag=implode(',',$oriTag);
        }
        //json custom_tag decode
        if($rows[0]->custom_tag != null){
            $rows[0]->custom_tag=implode(',',$rows[0]->custom_tag);
        }
        return view('Boards.form')->with([
            'params' => $params,
            $params['type'].'_menu' => 'active',
            'id' => $board_id,
            'rows' => $rows
        ]);
    }

    //게시물 수정
    public function update(Request $request,$board_id){
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $restore_check = $request->input('restore_check',false);
        $params = [
            'type'          => $request->input('type',null),
            'id'            =>  $request->input('id'),
            'title'         =>  $request->input('title'),
            'post'          =>  $request->input('url'),
            'sns_account'   =>  $request->input('sns_account'),
            'ori_thumbnail' =>  $request->input('ori_thumbnail'),
            'state'         =>  $request->input('state'),
            'gender'        =>  $request->input('gender'),
            'app_review'    =>  $request->input('app_review'),
            'text_check'    =>  $request->input('text_check'),
            'ori_tag'       =>  $request->input('ori_tag'),
            'custom_tag'    =>  $request->input("custom_tag"),
            'contents'      =>  $request->input('contents'),
            'created_at'    =>  $request->input('created_at',Carbon::now('Asia/Seoul')->toDateTimeString()),
        ];

        $params['created_at'] = Carbon::createFromTimeString($params['created_at']);

        $ori_tag = $collection = collect(explode(',', $params['ori_tag']))->toArray();

        //2019.01.29 cch 태그 중복 제거
        $ori_tag = explode(',',implode(',',array_unique($ori_tag)));

        $json_ori_tag = json_encode($ori_tag);

        $custom_tag = $collection = collect(explode(',',$params['custom_tag']))->toArray();

        //2019.01.29 cch 태그 중복 제거
        $custom_tag = explode(',',implode(',',array_unique($custom_tag)));

        $json_custom_tag=json_encode($custom_tag);


        $document = [
            "app"           =>  $app,
            'title'         =>  $params['title'],
            'post'           =>  $params['post'],
            'sns_account'   =>$params['sns_account'],
            'ori_thumbnail'  =>  $params['ori_thumbnail'],
            'gender'        =>  (int)$params['gender'],
            "state"         =>  (int)$params['state'],
            'app_review'    =>  $params['app_review'],
            'text_check'    =>  $params['text_check'],
            'contents'      =>  $params['contents'],
            "created_at"  =>  $params['created_at'],
            'updated_at'  =>  Carbon::now()
        ];

        if($params['type']!=null){
            $document['type']=$params['type'];
        }

        if($params['ori_tag'] != null)
            $document['ori_tag'] = $json_ori_tag;
        if($params['custom_tag'] != null)
            $document['custom_tag'] = $json_custom_tag;

        // file save
        $util = new Util();
        $path = 'images/'.$params['type'].'/';

        if ($request->hasFile('thumbnail'))
        {
            $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail'), $path,'pinxy',$params['type']);
            $document['thumbnail_url'] = "/".$path.$resized_image['filename'];
            $document['thumbnail_w'] = (int) $resized_image['width'];
            $document['thumbnail_h'] =  (int) $resized_image['height'];
        }

        if ($request->hasFile('data_files')) {
            $files = $request->File('data_files');
            foreach ($files as $file) {
                $file_type = $file->getMimeType();
                if (strstr($file_type, "video/")) {
                    $uploads[]=[
                        'type' => 'video',
                        'video_path' => 'video/fanfeed/src/',
                        'poster_path'   =>  'video/fanfeed/poster/',
                        'file' => $file
                    ];
                    $fanfeed_data['post_type'] ='video';
                } else if (strstr($file_type, "image/")) {
                    $uploads[] = [
                        'type' => 'image',
                        'path' => 'images/fanfeed/src/',
                        'file' => $file
                    ];
                    $fanfeed_data['post_type'] ='img';
                } else {
                    return $this->response->set_response(-1001, null);
                }
            }
            if(count($files)>1){
                $fanfeed_data['post_type'] ='post';
            }

            foreach ($uploads as $upload) {
                if($upload['type'] == 'video'){
                    $video_filename = $util->SaveFileAzure($upload['file'], $upload['video_path']);
                    $poster_filename = $util->SaveVideoPoster($upload['file'],$upload['poster_path'],$user);
                    $fanfeed_data['data'][] = [
                        $upload['type'] => [
                            'src' => "/" . $upload['video_path'] . $video_filename,
                            'poster'    =>  "/" . $upload['poster_path'] . $poster_filename,
                        ],
                    ];
                }elseif($upload['type'] == 'image'){
                    $filename = $util->SaveFileAzure($upload['file'], $upload['path']);
                    $fanfeed_data['data'][] = [
                        $upload['type'] => "/" . $upload['path'] . $filename
                    ];
                }
            }
            $document['data'] = json_encode($fanfeed_data['data']);
        }

        if($restore_check !='false'){
            $board = Board::withTrashed()->find($params['id']);
            $board->restore();
            $board->update($document);

            UpdateLog::create([
                'board_id'  =>  $board->id,
                'board_type' => $board->type,
                'update_name'   =>  'restore',
            ]);
        }else{
            // update db
            $board = Board::find($params['id']);
            $board->update($document);
            UpdateLog::create([
                'board_id'  =>  $board->id,
                'board_type' => $board->type,
                'update_name'   =>  'update'
            ]);
        }
        return redirect('/admin/boards?type='.$params['type']);
    }
    public function destroy(Request $request){
        $type = $request->input('type');
        $validator = $this->validate($request,[
            'check_item'=>'required'
        ]);
        $params = [
            'check_items' => $request->input('check_item'),
            'type'  =>  $request->input('type'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $boards = Board::when($params['type'] != null ,function($query) use($params){
            return $query->where('type',$params['type']);
        })->whereIn('id',$params['check_items']);
        $rows = $boards->get();

        foreach ($rows as $board){
            $logs[] = [
                'board_id'  =>  $board->id,
                'board_type' => $board->type,
                'update_name'   =>  'delete',
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()

            ];
        }
        UpdateLog::insert($logs);

        $boards->delete();

        return redirect()->back()->with([
            'params'    =>  $params
        ]);
    }

    public function app_review_update(Request $request){
        $params = [
            'Inspection' =>  $request->input('Inspection'),
            'check_items' => $request->input('check_item'),
            'type' => $request->input('type'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];
        if($params['Inspection']==1){
            $update_name = "Inspection on";
        }elseif($params['Inspection']==0){
            $update_name ="Inspection off";
        }
        $query = Board::whereIn('id', $params['check_items'])
           ->where('app_review','!=',$params['Inspection']);
        $boards = $query->get();
        $query->update([
                'app_review' =>  $params['Inspection']
            ]);

        foreach ($boards  as $board){
            $logs[] =[
                'board_id'      => $board->id,
                'board_type'    =>  $board->type,
                'update_name'   =>  $update_name,
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()
            ];
        }

        if(isset($logs)){
            UpdateLog::insert($logs);
        }

        return redirect()->back()->with([
            'params'    =>  $params
        ]);
    }
    public function gender_update(Request $request)
    {
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $type = $request->input('type');
        $gender = $request->input('change_gender');
        $params = [
            'check_items' => $request->input('check_item'),
            'individual' => $request->input('individual', false),
            'type' => $request->input('type'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        if ($gender == 1) {
            $update_name = 'man';
        } else {
            $update_name = 'woman';
        }

        $query = Board::when($params['type'] != null, function ($query) use ($params) {
            return $query->where('type', $params['type']);
        })
            ->where('gender', '!=', $gender)
            ->whereIn('id', $params['check_items']);

        //2019.04.26 cch 로그생성
        $boards = $query->get();
        foreach($boards as $board){
            $logs[] = [
                'board_id'      => $board->id,
                'board_type'    =>  $board->type,
                'update_name'   =>  $update_name,
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()
            ];
        }
        UpdateLog::insert($logs);

        $query->update([
                'gender' =>  (int)$gender
            ]);

        if (!$params['individual']) {
            return redirect()->back()->with([
                'params'    =>  $params
            ]);
        }
    }
    public function open_update(Request $request){

        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'type'      =>$request->input('type'),
            'change_state'     =>$request->input('change_state'),
            'individual'    =>  $request->input('individual',false),
            'from_app_review'=>$request->input('from_app_review',false),
            'add_tag'  =>    $request->input("send_tag"),
            'check_items' => $request->input('check_item'),
            'common_tags'   =>  $request->input('common_tags'),
            'app_review'    =>  $request->input('app_review',0),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        if($params['change_state'] ==1){
            $update_name = 'open';
        }else{
            $update_name = 'close';
        }

        if($params['add_tag'] != null){
            $params['add_tag']      = explode(',',$params['add_tag']);     //수정 후 공통 태그들
            foreach($params['add_tag'] as $tag){
                if($params['type'] == null){
                    DB::insert('insert ignore into tags (name,board,type) values (?,?,?),(?,?,?),(?,?,?),(?,?,?)',
                        [$tag,'instagram','custom',$tag,'youtube','custom',$tag,'news','custom',$tag,'web','custom']);
                }else{
                    DB::insert('insert ignore into tags (name,board,type) values (?,?,?)',[$tag,$params['type'],'custom']);
                }
            }
        }else{
            $params['add_tag']      = explode(',',$params['add_tag']);
        }

        $params['common_tags']  = explode(',',$params['common_tags']); //수정 전 공통 태그들

        $delete_tags = array_diff($params['common_tags'],$params['add_tag']);  //수정전 - 수정후 => 없어질 태그들
        $add_tags = array_diff($params['add_tag'],$params['common_tags']);      //수정후 - 수정전 => 추가할 태그들



        //개별 오픈 버튼일경우
        if($params['individual']){
            $board = Board::find($params['check_items'][0]);

            if($update_name == 'open'){
                if($board->gender = 2){
                    $board->update([
                        'state' =>  $params['change_state'],
//                        'created_at'    =>  Carbon::now()->toDateTimeString()
                    ]);
                }else{
                    $board->update([
                        'state' =>  $params['change_state'],
                    ]);
                }

            }else{
                $board->update([
                    'state' =>  $params['change_state'],
                ]);
            }

            //2019.04.26 cch update log용도

            UpdateLog::create([
                'board_id'      =>  $board->id,
                'board_type'    =>  $board->type,
                'update_name'   =>  $update_name
            ]);

            return;
        }


        $boards = Board::whereIn('id',$params['check_items'])
            ->when($params['type'] != null,function($query) use($params){
                return $query->where('type',$params['type']);
            })
            ->where('app',$app)
            ->get();

            if($params['add_tag'][0] == ""){
                foreach ($boards as $board) {
                    if($params['app_review']==1){
                        $board->app_review = $params['app_review'];
                    }
                    if($board->custom_tag == null)
                        $board->custom_tag=[];
                    else
                        $board->custom_tag=json_decode($board->custom_tag);

                    $prev_tag = json_encode($board->custom_tag);

                    $custom_tag= array_merge($board->custom_tag,$add_tags);
                    $custom_tag =array_diff($custom_tag,$delete_tags);

                    //2019.01.29 cch 태그 중복 체크 후 제거
                    $custom_tag = explode(',',implode(',',array_unique($custom_tag)));

                    $board->custom_tag = json_encode($custom_tag);//다시 json으로

                    $board->state = $params['change_state'];
                    $board->save();

                    //2019.04.26 cch update log용도
                    $logs[] = [
                        'board_id'      =>  $board->id,
                        'board_type'    =>  $board->type,
                        'prev_tag'      =>  $prev_tag,
                        'after_tag'     =>  $board->custom_tag,
                        'update_name'   =>  $update_name,
                        'created_at'    =>  Carbon::now()->toDateTimeString(),
                        'updated_at'    =>  Carbon::now()->toDateTimeString()
                    ];
                }
            }else{
                foreach ($boards as $board) {
                    if($params['app_review']==1){
                        $board->app_review = $params['app_review'];
                    }
                    if($board->custom_tag == null)
                        $board->custom_tag=[];
                    else
                        $board->custom_tag=json_decode($board->custom_tag);
                    $prev_tag = json_encode($board->custom_tag);

                    $custom_tag= array_merge($board->custom_tag,$add_tags);
                    $custom_tag =array_diff($custom_tag,$delete_tags);

                    //2019.01.29 cch 태그 중복 체크 후 제거
                    $custom_tag = explode(',',implode(',',array_unique($custom_tag)));
                    $board->custom_tag = json_encode($custom_tag);//다시 json으로
                    $board->state = $params['change_state'];
//                    if($update_name =='open' && $board->gender ==2 ){
//                        $board->created_at = Carbon::now();
//                    }
                    $board->save();

                    $logs[] = [
                        'board_id'      =>  $board->id,
                        'board_type'    =>  $board->type,
                        'prev_tag'      =>  $prev_tag,
                        'after_tag'     =>  $board->custom_tag,
                        'update_name'   =>  $update_name,
                        'created_at'    =>  Carbon::now()->toDateTimeString(),
                        'updated_at'    =>  Carbon::now()->toDateTimeString()
                    ];
                }
            }

            UpdateLog::insert($logs);

        return redirect()->back()->with([
            'params'    =>  $params
        ]);
    }


    public function text_update(Request $request){
        $validator = $this->validate($request,[
            'check_item'=>'required'
        ]);

        $params = [
            'type'  =>  $request->input('type',null),
            'individual'=>  $request->input('individual',false),
            'text'  =>  $request->input('change_text_check'),
            'check_items'   =>  $request->input('check_item'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];
        if($params['text'] == 1){
            $update_name = 'text_not';
        }elseif ($params['text']==2){
            $update_name = 'text_exist';
        }

        if($params['individual']){
            Board::when($params['type'] !=null ,function($query) use($params) {
                return $query->where('type',$params['type']);
            })->whereIn('id',$params['check_items'])
                ->update([
                    'text_check' => $params['text'],
                ]);
            $board_type = Board::find($params['check_items'][0])->type;

            UpdateLog::create([
                'board_id' => $params['check_items'][0],
                'board_type'    => $board_type,
                'update_name'   => $update_name
            ]);

            return;
        }

        $boards = Board::when($params['type'] !=null ,function($query) use($params) {
            return $query->where('type',$params['type']);
        })
        ->whereIn('id',$params['check_items'])
        ->get();

        //2차 최적화 api 한번에 이미지 10개 제한하기
        $board_count = count($boards);
        $feature =  new Feature();
        $feature->setType(5);                     //1 => face_check , 5 => text_check

        for ($k=0,$i=0 ; $k < $board_count; $k++){
            $path = env('CDN_URL').$boards[$k]->thumbnail_url;
            $imagesoure = new ImageSource();
            $imagesoure->setImageUri($path);
            $image = new Image();
            $image->setSource($imagesoure);
            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeatures([$feature]);
            $requests[] = $request;

            if(($k+1 == $board_count && $board_count <10) ||($k%10 == 9) || ($k+1 == $board_count && $board_count > 10)){                                    // 10개미만일경우 + 이미지 10개마다 요청
                $imageannotator_client = new ImageAnnotatorClient();
                $responses = $imageannotator_client->batchAnnotateImages($requests)->getResponses();
                for($i;$i<=$k;$i++){
                    $text_count = count($responses[$i%10]->getTextAnnotations());
                    if($text_count>0){
                        $text_exist[] = $boards[$i]->id;
                    }else{
                        $text_not[] = $boards[$i]->id;
                    }
                }
                $imageannotator_client->close();
                $requests = [];
            }
            $logs[]=[
                'board_id'      =>  $boards[$k]->id,
                'board_type'    =>  $boards[$k]->type,
                'update_name'   =>  'text_check',
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()
            ];
        }

        if(isset($text_exist)){
            Board::whereIn('id',$text_exist)->update([
                'text_check'  =>  2
            ]);
        }
        if(isset($text_not)){
            Board::whereIn('id',$text_not)->update([
                'text_check'  =>  1
            ]);
        }

        UpdateLog::insert($logs);
        return redirect()->back()->with([
            'params'    =>  $params
        ]);
    }

    public function face_update(Request $request){
        $validator = $this->validate($request,[
            'check_item'=>'required'
        ]);

        $params = [
            'type'  =>  $request->input('type',null),
            'individual'=>  $request->input('individual',false),
            'text'  =>  $request->input('change_text_check'),
            'check_items'   =>  $request->input('check_item'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $boards = Board::when($params['type'] !=null ,function($query) use($params) {
            return $query->where('type',$params['type']);
        })
            ->whereIn('id',$params['check_items'])
            ->get();
        //////////////////////////////////////////////////////////////////////////////////////////////////
        $board_count = count($boards);
        $feature =  new Feature();
        $feature->setType(1);                     //1 => face_check , 5 => text_check
        $i = 0;
        for ($k=0 ; $k < $board_count; $k++){
            $path = env('CDN_URL').$boards[$k]->thumbnail_url;
            $imagesoure = new ImageSource();
            $imagesoure->setImageUri($path);
            $image = new Image();
            $image->setSource($imagesoure);
            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeatures([$feature]);
            $requests[] = $request;
            if(($k+1 == $board_count && $board_count <10) ||($k%10 == 9) || ($k+1 == $board_count && $board_count > 10)){                                    // 10개미만일경우 + 이미지 10개마다 요청
                $imageannotator_client = new ImageAnnotatorClient();
                $responses = $imageannotator_client->batchAnnotateImages($requests)->getResponses();
                for($i;$i<=$k;$i++){
                    $face_count = count($responses[$i%10]->getFaceAnnotations());
                    $boards[$i]->update([
                        'face_check'   => $face_count
                    ]);
                }
                $imageannotator_client->close();
                $requests = [];
            }
            $logs[]=[
                'board_id'      =>  $boards[$k]->id,
                'board_type'    =>  $boards[$k]->type,
                'update_name'   =>  'face_check',
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()
            ];
        }

        UpdateLog::insert($logs);

        return redirect()->back()->with([
            'params'    =>  $params
        ]);
    }


    public function tag_update(Request $request){
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $params=[
            'type'  =>      $request->input('type'),
            'individual'    =>  $request->input('individual',false),
            'add_tag'  =>    $request->input("send_tag"),
            'check_items' => $request->input('check_item'),
            'common_tags'   =>  $request->input('common_tags'),
            'last' => $request->input('last'),
            'state' => $request->input('state'),
            'gender' => $request->input('gender'),
            'tags' => $request->input('tags'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];


        if($params['add_tag'] != null){
            $params['add_tag']      = explode(',',$params['add_tag']);     //수정 후 공통 태그들
            foreach($params['add_tag'] as $tag){
                if($params['type'] == null){
                    DB::insert('insert ignore into tags (name,board,type) values (?,?,?),(?,?,?),(?,?,?),(?,?,?)',
                        [$tag,'instagram','custom',$tag,'youtube','custom',$tag,'news','custom',$tag,'web','custom']);
                }else{
                    DB::insert('insert ignore into tags (name,board,type) values (?,?,?)',[$tag,$params['type'],'custom']);
                }
            }
        }else{
            $params['add_tag']      = explode(',',$params['add_tag']);
        }

        $params['common_tags']  = explode(',',$params['common_tags']); //수정 전 공통 태그들

        $delete_tags = array_diff($params['common_tags'],$params['add_tag']);  //수정전 - 수정후 => 없어질 태그들

        $add_tags = array_diff($params['add_tag'],$params['common_tags']);      //수정후 - 수정전 => 추가할 태그들

        $boards = Board::whereIn('id',$params['check_items'])
            ->when($params['type'] !=null,function($query) use($params){
                return $query->where('type',$params['type']);
            })
            ->where('app',$app)
            ->get();

        foreach($boards as $board){
            if($board->custom_tag == null)
                $board->custom_tag=[];
            else
                $board->custom_tag=json_decode($board->custom_tag);

            $prev_tag = json_encode($board->custom_tag);

            $custom_tag= array_merge($board->custom_tag,$add_tags);
            $custom_tag =array_diff($custom_tag,$delete_tags);

            //2019.01.29 cch 태그 중복 체크 후 제거
            $custom_tag = explode(',',implode(',',array_unique($custom_tag)));

            $board->custom_tag = json_encode($custom_tag);//다시 json으로
            $board->save();

            $logs[] = [
                'board_id'      =>  $board->id,
                'board_type'    =>  $board->type,
                'prev_tag'      =>  $prev_tag,
                'after_tag'     =>  $board->custom_tag,
                'update_name'   =>  'tag_update',
                'created_at'    =>  Carbon::now()->toDateTimeString(),
                'updated_at'    =>  Carbon::now()->toDateTimeString()
            ];
        }

        UpdateLog::insert($logs);

        if($params['individual']){
            return response()->json([
               'result' =>  'done'
            ]);
        }
        return redirect()->back()->with([
           'params' =>  $params
        ]);
    }

    public function common_tag(Request $request){
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'type'  =>  $request->input('type'),
            'check_items' => $request->input('check_item')
        ];


        $boards = Board::whereIn('id',$params['check_items'])
            ->when($params['type'] != null,function($query) use($params){
                return $query->where('type',$params['type']);
            })
            ->where('app',$app)
            ->get();

        if(json_decode($boards[0]->custom_tag) != '' || json_decode($boards[0]->custom_tag) != null) {
            $result = json_decode($boards[0]->custom_tag);
            foreach ($boards as $new) {
                if ($new->custom_tag != null || json_decode($new->custom_tag) != '') {
                    $result = array_intersect($result, json_decode($new->custom_tag));
                }else{
                    $result=[];
                }
            }
        }else{
            $result = [];
        }

        $string_common_tags=implode(',',$result);
        if(count($params['check_items'])==1){
            $article = Board::where('id',$params['check_items'][0])
                ->when($params['type'] != null,function($query) use($params){
                    return $query->where('type',$params['type']);
                })
                ->where('app',$app)
                ->get();
            $ori_tags=[];
            if($article[0]->ori_tag != '' || $article[0]->ori_tag != null) {
                $ori_tags = $article[0]->ori_tag;
            }
            return json_encode([
                'result'  =>  $result,
                'common_tags'=>$string_common_tags,
                'ori_tags'  =>  $ori_tags
            ]);
        }
        return json_encode([
            'result'  =>  $result,
            'common_tags'=>$string_common_tags
        ]);
    }

    public function app_review(Request $request){
        $params = [
            'board_type'    => $request->input('board_type',null),
            'start_date' => $request->input('start_date','2019-01-15'),
            'end_date' => $request->input('end_date',Carbon::now()->toDateString()),
            'state' => $request->input('state'),
            'gender' => $request->input('gender', 1),
            'app_review'    =>  $request->input('app_review',0)
        ];

        $starttime = Carbon::createFromTimeString($params['start_date'].' 00:00:00');
        $endtime = Carbon::createFromTimeString($params['end_date'].' 23:59:59');



        $boards= Board::when($params['board_type']!=null,function($query) use($params) {
            return $query->where('type',$params['board_type']);
        })
            ->when($params['state']!=null,function($query) use ($params){
                return $query->where('state',(int)$params['state']);
            })
            ->where('gender',$params['gender'])
            ->whereBetween('created_at', [$starttime, $endtime])
            ->where('app_review',$params['app_review']);
        $total_cnt = $boards->count();
        $page_cnt = 50;
        $rows = $boards->orderBy('created_at','desc')
            ->Paginate($page_cnt);

        {{ $rows->appends($params)->links(); }}


        return view('Boards.app_review')->with([
            'review_menu' => 'active',
            'params' => $params,
            'rows' => $rows,
            'total' => $total_cnt
        ]);
    }


    public function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function getSiteOG( $url, $specificTags=0 ){
        $doc = new DOMDocument();
        @$doc->loadHTML(file_get_contents($url));
        $res['title'] = $doc->getElementsByTagName('title')->item(0)->nodeValue;

        foreach ($doc->getElementsByTagName('meta') as $m){
            $tag = $m->getAttribute('name') ?: $m->getAttribute('property');
            if(in_array($tag,['description','keywords']) || strpos($tag,'og:')===0) $res[str_replace('og:','',$tag)] = $m->getAttribute('content');
        }
        return $specificTags? array_intersect_key( $res, array_flip($specificTags) ) : $res;
    }

    //네이버 news api
    public function get_news(Request $request) {

        $client_id = "QI4CBOw2COVcXoMmVb0_";
        $client_secret = "XRgjR9vD0M";
        $encText = urlencode("김준수");
        $url = "https://openapi.naver.com/v1/search/news.json?query=".$encText; // json 결과

        $is_post = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = "X-Naver-Client-Id: ".$client_id;
        $headers[] = "X-Naver-Client-Secret: ".$client_secret;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "status_code:".$status_code."";
        curl_close ($ch);

        if($status_code == 200) {
            $array_data = json_decode($response, true);
            //echo $array_data;
            print_r($array_data['items']);

            $user = $request->user();

            if ($user != null) {
                $params['app'] = $user->app;
            } else {
                $params['app'] = 'fantaholic';
            }

            foreach ($array_data['items'] as $item) {
                $document = [
                    'app' => $params['app'],
                    'type' => 'news',
                    'title' => $item['title'],
                    'contents' => $item['description'],
                    'recorded_at' => strftime("%Y-%m-%d %H:%M:%S", strtotime($item['pubDate']))
                ];

                $html = $this->file_get_contents_curl($item['originallink']);

                $doc = new \DOMDocument();
                @$doc->loadHTML($html);

                $metas = $doc->getElementsByTagName('meta');

                $img_url = "";
                for ($i = 0; $i < $metas->length; $i++)
                {
                    $meta = $metas->item($i);
                    if($meta->getAttribute('property') == 'og:image')
                        $img_url = $meta->getAttribute('content');
                }
                $document['ori_thumbnail'] = $img_url;






                // file save
                $util = new Util();
                $path = 'images/'.'news'.'/thumbnail/';

                $resized_image = $util->AzureUploadImage($img_url, $path, 640, '');
//                dd($resized_image);
                $image_save = new \stdClass();
                $image_save->image = "/".$path.$resized_image['fileName'];
//                $data[] = $image_save;
//                dd($data);
                $data = [
                    $image_save
                ];
                $document['data'] = $data;
                $document['thumbnail_url'] = $image_save->image;
//                $document['data'] = '[{"image":"'.$image_save->image.'"}]';
                $ori_data = [
                    $resized_image['path']
                ];
                $document['ori_data'] = $ori_data;
                //dd($data);




                $board = Board::create($document);
            } //for문



            
            
            
        }
    }


    public function naver_news() {
        $news = new News();
        $news->getChannelContents();
    }




















}
