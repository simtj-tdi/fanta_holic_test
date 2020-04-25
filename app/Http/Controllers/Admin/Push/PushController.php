<?php
//namespace app\Http\Controllers\Admin\Push;
namespace App\Http\Controllers\Admin\Push;

use App\Ad;
use App\Campaign;
use App\Http\Controllers\Controller as BaseController;
use App\Lib\AzureDocumentDB\AzureDocumentDB;
use App\Push;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Lib\Util;

class PushController extends BaseController
{
    // push 리스트
    public function index(Request $request){
        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'state' => $request->input('state', 'Y'),
            'start_date' => $request->input('start_date', Carbon::now()->addDays(-700)->toDateString()),
            'end_date' => $request->input('end_date', Carbon::now()->toDateString())
        ];

        $rows = Push::where('app', $app)
            ->where('managed_type', 'M')
            ->where('state', $params['state'])
            ->whereBetween('created_at', [$params['start_date']." 00:00:00", $params['end_date']." 23:59:59"])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('push.index')->with([
            'title' => 'Push 관리',
            'push_menu' => 'active',
            'params' => $params,
            'rows' => $rows,

            'config' => [
                'push_type' => [
                    'T' => 'Text',
                    'I' => 'Image'
                ],
                'action' => [
                    'M' => '이동',
                    'A' => '앱 실행',
                    'B' => '특정 게시물로 이동',
                    'S' =>  '멜론 스트리밍'
                ],
                'state' => [
                    'R' => '대기',
                    'S' => '발송중',
                    'Y' => '발송 완료',
                    'X' => '발송 취소',
                ]
            ]
        ]);
    }
    //생성 화면
    public function create(Request $request){
        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $batch_type = $request->input('batch_type', 'A');
        $user_id = $request->input('user_id', 0);

        $rows = null;
        $melon_streaming_campaigns = Campaign::where('app',$app)->where('event_type','M')
            ->where('state',1)
            ->get();

        return view('push.form')->with([
            'title' => 'Push 관리',
            'push_menu' => 'active',
            'id' => 0,
            'rows' => $rows,
            'batch_type' => $batch_type,
            'user_id' => $user_id,
            'melon_streaming_campaigns'  =>  $melon_streaming_campaigns,
            'config' => [
                'state' => [
                    'R' => '대기',
                    'S' => '발송중',
                    'Y' => '발송 완료',
                    'X' => '발송 취소',
                ]
            ]
        ]);
    }
    //생성
    public function store(Request $request){
        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $this->validate($request, [
            'action'    =>  'required',
            'title' => 'required|string',
            'contents' => 'required|string',
            'tick' => 'required|string',
        ]);

//        $app = Session::get('app', Auth::user()->app);
        $params = [
            'batch_type' => $request->input('batch_type', 'A'),
            'user_id' => $request->input('user_id', 0),
            'title' => $request->input('title'),
            'contents' => $request->input('contents'),
            'tick' => $request->input('tick'),
            'push_type' => $request->input('push_type'),
            'action' => $request->input('action'),
            'url' => $request->input('url'),
            'board_type' => $request->input('board_type'),
            'board_id' => $request->input('board_id'),
            'campaign_id' =>  $request->input('campaign_id'),
            'start_date' => $request->input('start_date'),
        ];

        // file save
        $uploads = null;
        if ($request->hasFile('img_url'))
        {
            $util = new Util();
            $path = 'images/push/';
            $filename = $util->SaveThumbnailAzure($request->file('img_url'), $path);
            $uploads = env('CDN_URL')."/".$path.$filename;
        }

        // 멜론 스트리밍 get)
        //todo 멜론스트리밍 타입 => 멜론 광고 관리부터 선행작업필요
        if($params['action'] == 'S'){
            $melon_campaign = Campaign::where('id',$params['campaign_id'])->get()->last();
            $params['streaming_url'] = json_encode($melon_campaign);
            $params['managed_type'] ='S';
        }else{
            $params['managed_type'] = 'M';
        }

        for ($i=0;$i<$request->input('many',1);$i++){
            Push::create([
                'app' => $app,
                'batch_type' => $params['batch_type'],
                'managed_type' => $params['managed_type'],
                'user_id' => $params['user_id'],
                'title' => $params['title'],
                'content' => $params['contents'],
                'tick' => $params['tick'],
                'push_type' => $params['push_type'],
                'img_url' => $uploads,
                'action' => $params['action'],
                'url' => $params['url'],
                'board_type' => $params['board_type'],
                'board_id' => $params['board_id'],
                'streaming_url' =>  isset($params['streaming_url']) ?  $params['streaming_url'] : '',
                'campaign_id' =>  isset($params['campaign_id']) ?  $params['campaign_id'] : '',
                'start_date' => $params['start_date'],
            ]);
        }
        return redirect('/admin/pushes');
    }
    //수정화면
    public function edit(Request $request,$push_id){
        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $batch_type = $request->input('batch_type', 'A');

        $user_id = $request->input('user_id', 0);
        $rows = Push::where('app', $app)
            ->where('id', $push_id)
            ->limit(1)
            ->get()
            ->last();

        $batch_type = $rows->batch_type;

        $melon_streaming_campaigns = Campaign::where('app',$app)->where('event_type','M')
            ->get();
        return view('push.form')->with([
            'title' => 'Push 관리',
            'push_menu' => 'active',
            'id' => $push_id,
            'rows' => $rows,
            'batch_type' => $batch_type,
            'user_id' => $user_id,
            'melon_streaming_campaigns'  =>  $melon_streaming_campaigns,
            'config' => [
                'state' => [
                    'R' => '대기',
                    'S' => '발송중',
                    'Y' => '발송 완료',
                    'X' => '발송 취소',
                ]
            ]
        ]);
    }

    //수정
    public function update(Request $request,$push_id){
        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $params = [
            'title' => $request->input('title'),
            'contents' => $request->input('contents'),
            'tick' => $request->input('tick'),
            'push_type' => $request->input('push_type'),
            'action' => $request->input('action'),
            'url' => $request->input('url'),
            'board_type' => $request->input('board_type'),
            'board_id' => $request->input('board_id'),
//            'streaming_url' =>  $request->input('streaming_url'),
            'start_date' => $request->input('start_date'),
        ];

        // file save
        if ($request->hasFile('img_url'))
        {
            $util = new Util();
            $path = 'images/push/';
            $filename = $util->SaveThumbnailAzure($request->file('img_url'), $path);
            $params['img_url'] = env('CDN_URL')."/".$path.$filename;
        }

        //todo 멜론 스트리밍 광고리스트 선행작업

        Push::where('app', $app)
            ->where('id', $push_id)
            ->update($params);

        return redirect('/admin/'.$push_id.'/edit');

    }
    //삭제
    public function destroy(Request $request){

        //todo 앱구분
        $user = $request->user();
        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'check_itmes' => $request->input('check_item')
        ];

        Push::where('app', $app)
            ->where('state', 'R')
            ->whereIn('id', $params['check_itmes'])
            ->update([
                'state' => 'X'
            ]);
        return redirect('/admin/pushes');
    }
}
