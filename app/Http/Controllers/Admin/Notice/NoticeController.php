<?php

namespace App\Http\Controllers\Admin\Notice;

use App\Notice;
use App\Lib\Log;
use App\Lib\Util;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class NoticeController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'page_cnt'  =>  $request->input('page_cnt',10),
            'start_date'=>$request->input('start_date',Carbon::now()->addMonths(-7)->toDateString()),
            'end_date'=>$request->input('end_date',Carbon::tomorrow()->toDateString()),
            'type'  =>  $request->input('type','A')
        ];
        $rows = Notice::where('app',$app)
            ->when($params['type'],function($query)use($params){
                $query->where('type',$params['type']);
            })
            ->whereBetween('created_at',[$params['start_date'],$params['end_date']])
            ->paginate($params['page_cnt']);

        return view('notice.index')->with([
            'rows' =>$rows,
            'params'    =>  $params,
            'search_count'  =>  $rows->count(),
            'total'     =>Notice::where('app',$app)->count()
        ]);
    }

    public function store(Request $request){
        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        try {
            $request->validate([
                'create_title' => 'required|string',
                'create_contents' => 'required|string',
            ]);

        } catch (ValidationException $e) {

            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::REQUEST_INVALID_DATA,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 200);
        }

        $document = [
            'app'   =>  $app,
            'type'  =>  'A',
            'managed_type'  =>  'M',
            'title'    =>   $request->input('create_title'),
            'contents'  =>  $request->input('create_contents')
        ];

        $util = new Util();
        $path = $app.'/images/notice/thumbnail/';
        //썸네일 있으면 cnd 업로드
        if($request->hasFile('create_thumbnail')){
            $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('create_thumbnail'), $path,$app,'notice');
            $document['thumbnail_url'] = "/".$path.$resized_image['filename'];
//            $document['thumbnail_w'] = (int) $resized_image['width'];
//            $document['thumbnail_h'] =  (int) $resized_image['height'];
        }

        //이미지 컨텐츠 파일들 있으면 cnd 업로드
        $path = $app.'/images/notice/data/';
        if($request->hasFile('create_content_images')){
            $data = [];
            foreach($request->allFiles('create_content_images') as $file){
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($file, $path,$app,'notice');
                $image_save = new \stdClass();
                $image_save->image = "/".$path.$resized_image['filename'];
                $data[] = $image_save;
            }
            $document['data'] = json_encode($data);
        }

        Notice::create($document);

        return redirect('/admin/notices');
    }

    public function destroy(Request $request){
        dd('todo 공지 삭제');
    }

    public function edit(Request $request){
        dd('todo 공지 수정');
    }

}
