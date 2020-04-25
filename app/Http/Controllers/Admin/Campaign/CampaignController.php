<?php

namespace App\Http\Controllers\Admin\Campaign;

use App\Campaign;
use App\EqualScoreHistory;
use App\Lib\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    //목록
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
            'state'=>$request->input('state',''),
        ];

         $rows = Campaign::where('app',$app)
             ->when($params['state'] != '',function($query) use($params) {
                 return $query->where('state',$params['state']);
             })->whereBetween('created_at',[$params['start_date'],$params['end_date']])
             ->paginate($params['page_cnt']);

        return view('Campaign.index')->with([
            'rows' =>$rows,
            'params'    =>  $params,
            'search_count'  =>  $rows->count(),
            'total'     =>Campaign::where('app',$app)->count()
        ]);
    }

    //새 게시물등록창
    public function create(Request $request){
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params=[
        ];
        return view('Campaign.form')->with([
            'title' => 'Push 관리',
            'campaign_menu' => 'active',
            'id' => 0,
            'params'    =>  $params
        ]);
    }

    public function store(Request $request){

        $validator = $this->validate($request,[
            'event_type'     =>  'required',
            'title'          =>  'required',
            'url'            =>  'required',
            'item_count'     =>  'required',
            'start_date'     =>  'required',
            'end_date'       =>  'required'
        ]);

        if($request->input('event_type' == 'M')){
            $validator = $this->validate($request,[
                'repeat'          =>  'required',
                'app_package'            =>  'required',
                'push_title'     =>  'required',
                'push_tick'     =>  'required',
                'push_message'       =>  'required'
            ]);
        }

        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'event_type'                 =>  $request->input('event_type'),
            'order_num'                  =>  $request->input('order_num',0),
            'repeat'                     =>  $request->input('repeat'),
            'img_url'                    =>  $request->input('img_url'),
            'app_package'                =>  $request->input('app_package'),
            'push_title'                 =>  $request->input('push_title'),
            'push_message'               =>  $request->input('push_message'),
            'push_tick'                  =>  $request->input('push_tick'),
            'thumbnail_1_1'              =>  $request->input('thumbnail_1_1'),
            'thumbnail_2_1'              =>  $request->input('thumbnail_2_1'),
            'thumbnail_3_1'              =>  $request->input('thumbnail_3_1'),
            'thumbnail_1_2'              =>  $request->input('thumbnail_1_2'),
            'thumbnail_2_2'              =>  $request->input('thumbnail_2_2'),
            'thumbnail_3_3'              =>  $request->input('thumbnail_3_3'),
            'title'                      =>  $request->input('title'),
            'description'                =>  $request->input('description'),
            'url'                        =>  $request->input('url'),
            'item_count'                 =>  $request->input('item_count'),
            'start_date'                 =>  $request->input('start_date'),
            'end_date'                   =>  $request->input('end_date'),
        ];

        //공통
        $document = [
            'state' => 0,
            'app'   =>  $app,
            'title' =>  $params['title'],
            'event_type'    =>  $params['event_type'],
            'description'   =>  $params['description'],
            'url'       =>  $params['url'],
            'item_count'    =>  $params['item_count'],
            'start_date'    =>  Carbon::createFromFormat('Y-m-d',$params['start_date']),
            'end_date'      =>  Carbon::createFromFormat('Y-m-d',$params['end_date']),
        ];

//        ('광고 타입 [M=멜론 스트리밍 , I = 설치형, F= 친구초대, C =클릭형]');
        if($params['event_type'] == 'M'){
            $util = new Util();
            $path = $app.'/images/campaign/logo/';

            if ($request->hasFile('img_url'))
            {
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('img_url'), $path,$app,'campaign');
                $document['img_url']  = "/".$path.$resized_image['filename'];
            }else{
                return redirect()->back()->with(['message'  =>  'need logo']);
            }
            $document['order_num']  = $params['order_num'];
            $document['repeat']     = $params['repeat'];
            $document['app_package']=$params['app_package'];
            $document['push_title'] =$params['push_title'];
            $document['push_tick']  =$params['push_tick'];
            $document['psuh_message']=$params['push_message'];

        }elseif ($params['event_type'] == 'I'){

        }elseif ($params['event_tyoe'] == 'F'){

        }elseif ($params['event_tyoe'] == 'C'){

        }

        Campaign::create($document);
        return redirect('/admin/Campaigns');
    }

    public function edit(Request $request,$id){
        $campaign = Campaign::find($id);
        return view('Campaign.form')->with([
            'campaign'  =>  $campaign
        ]);
    }

    public function update(Request $request){
        dd('update');
    }

    public function destroy(Request $request){
        dd('destroy');
    }
}
