<?php
namespace App\Http\Controllers\Push;

use App\Http\Controllers\Controller as BaseController;
use App\Lib\AzureDocumentDB\AzureDocumentDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Lib\Util;

class PushTestController extends BaseController
{
    // push 리스트
    public function index(Request $request)
    {
        $app = Session::get('app', Auth::user()->app);

        $params = [
            'state' => $request->input('state', 'R'),
            'start_date' => $request->input('start_date', Carbon::now()->addDays(-7)->toDateString()),
            'end_date' => $request->input('end_date', Carbon::now()->toDateString())
        ];

        $rows = DB::table('test_push')
            ->where('app', $app)
            ->where('managed_type', 'M')
            ->where('state', $params['state'])
            ->whereBetween('created_date', [$params['start_date']." 00:00:00", $params['end_date']." 23:59:59"])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('push.test.index')->with([
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

    // Push 등록 or 업데이트 form
    public function form(Request $request, $id)
    {
        $app = Session::get('app', Auth::user()->app);
        $batch_type = $request->input('batch_type', 'A');
        $user_id = $request->input('user_id', 0);

        $rows = null;
        if ($id != '0') {
            $rows = DB::table('test_push')
                ->where('app', $app)
                ->where('id', $id)
                ->limit(1)
                ->get()
                ->last();
            $batch_type = $rows->batch_type;
        }


        // 멜론스트리밍 목록 불러오기 => todo 충전소 안에서만 뽑는거면 문제없음 => 다른 노래들도 스트리밍 할려면 구조변경필요
        $documentdb = new AzureDocumentDB(env('AZURE_COSMOS_SQL_ENDPOINT'), env('AZURE_COSMOS_SQL_KEY'), false);

        $documentdb->get('database')->select(env('AZURE_COSMOS_SQL_DB'));
        $documentdb->get('collection')->select('ads');
        $docs = $documentdb->get('document')->partition_ranges('ads');

        $melon_streamings = new \stdClass();

        foreach($docs->PartitionKeyRanges as $partion){
            $document= $documentdb->get('document')
                ->query_option("SELECT ads.id,ads.title
                            FROM ads 
                            WHERE ads.app='{$app}' AND ads.deleted = 0 
                            AND ads.event_type= 'M'
                            ORDER BY ads.order_num ASC",
                    array(),
                    [
                        'x-ms-max-item-count: -1',
                        'x-ms-documentdb-query-enablecrosspartition: True',
                        'x-ms-documentdb-partitionkeyrangeid: '.$docs->_rid.','.$partion->id
                    ]);
            $melon_streamings->_rid = $document->_rid;
            $melon_streamings->_count = isset($melon_streamings->_count)? $melon_streamings->_count + $document->_count : $document->_count;
            $melon_streamings->Documents = isset($melon_streamings->Documents)? array_merge_recursive($melon_streamings->Documents, $document->Documents)
                : $document->Documents;
        }

        return view('push.test.form')->with([
            'title' => 'Push 관리',
            'push_menu' => 'active',
            'id' => $id,
            'rows' => $rows,
            'batch_type' => $batch_type,
            'user_id' => $user_id,
            'melon_streamings'  =>  $melon_streamings->Documents,
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

    // Push 등록
    public function store(Request $request)
    {
        $app = Session::get('app', Auth::user()->app);

        $this->validate($request, [
            'action'    =>  'required',
            'title' => 'required|string',
            'contents' => 'required|string',
            'tick' => 'required|string',
        ]);

        $app = Session::get('app', Auth::user()->app);
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
            'streaming_url' =>  $request->input('streaming_url'),
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

        if($params['action'] == 'S') {
            // 멜론스트리밍 불러오기 => todo 충전소 안에서만 뽑는거면 문제없음 => 다른 노래들도 스트리밍 할려면 구조변경필요
            $documentdb = new AzureDocumentDB(env('AZURE_COSMOS_SQL_ENDPOINT'), env('AZURE_COSMOS_SQL_KEY'), false);

            $documentdb->get('database')->select(env('AZURE_COSMOS_SQL_DB'));
            $documentdb->get('collection')->select('ads');
            $docs = $documentdb->get('document')->partition_ranges('ads');

            $melon_streamings = new \stdClass();

            foreach ($docs->PartitionKeyRanges as $partion) {
                $document = $documentdb->get('document')
                    ->query_option("SELECT *
                                FROM ads 
                                WHERE ads.app='{$app}' AND ads.deleted = 0 
                                AND ads.event_type= 'M'
                                AND ads.id = '{$params['streaming_url']}'
                                ORDER BY ads.order_num ASC",
                        array(),
                        [
                            'x-ms-max-item-count: 1',
                            'x-ms-documentdb-query-enablecrosspartition: True',
                            'x-ms-documentdb-partitionkeyrangeid: ' . $docs->_rid . ',' . $partion->id
                        ]);
                $melon_streamings->_rid = $document->_rid;
                $melon_streamings->_count = isset($melon_streamings->_count) ? $melon_streamings->_count + $document->_count : $document->_count;
                $melon_streamings->Documents = isset($melon_streamings->Documents) ? array_merge_recursive($melon_streamings->Documents, $document->Documents)
                    : $document->Documents;
            }
            $params['streaming_url'] = json_encode($melon_streamings->Documents[0]);

        }
        DB::table('test_push')->insertGetId([
            'app' => $app,
            'batch_type' => $params['batch_type'],
            'user_id' => $params['user_id'],
            'title' => $params['title'],
            'contents' => $params['contents'],
            'tick' => $params['tick'],
            'push_type' => $params['push_type'],
            'img_url' => $uploads,
            'action' => $params['action'],
            'url' => $params['url'],
            'board_type' => $params['board_type'],
            'board_id' => $params['board_id'],
            'streaming_url' =>  isset($params['streaming_url']) ? $params['streaming_url'] : '' ,
            'start_date' => $params['start_date'],
        ]);

        return redirect('/push/test');
    }

    // Push 수정
    public function update(Request $request)
    {
        $app = Session::get('app', Auth::user()->app);
        $params = [
            'title' => $request->input('title'),
            'contents' => $request->input('contents'),
            'tick' => $request->input('tick'),
            'push_type' => $request->input('push_type'),
            'action' => $request->input('action'),
            'url' => $request->input('url'),
            'board_type' => $request->input('board_type'),
            'board_id' => $request->input('board_id'),
            'streaming_url' =>  $request->input('streaming_url'),
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

        // 멜론스트리밍 불러오기 => todo 충전소 안에서만 뽑는거면 문제없음 => 다른 노래들도 스트리밍 할려면 구조변경필요
        $documentdb = new AzureDocumentDB(env('AZURE_COSMOS_SQL_ENDPOINT'), env('AZURE_COSMOS_SQL_KEY'), false);

        $documentdb->get('database')->select(env('AZURE_COSMOS_SQL_DB'));
        $documentdb->get('collection')->select('ads');
        $docs = $documentdb->get('document')->partition_ranges('ads');

        $melon_streamings = new \stdClass();

        foreach($docs->PartitionKeyRanges as $partion){
            $document= $documentdb->get('document')
                ->query_option("SELECT *
                            FROM ads 
                            WHERE ads.app='{$app}' AND ads.deleted = 0 
                            AND ads.event_type= 'M'
                            AND ads.id = '{$params['streaming_url']}'
                            ORDER BY ads.order_num ASC",
                    array(),
                    [
                        'x-ms-max-item-count: 1',
                        'x-ms-documentdb-query-enablecrosspartition: True',
                        'x-ms-documentdb-partitionkeyrangeid: '.$docs->_rid.','.$partion->id
                    ]);
            $melon_streamings->_rid = $document->_rid;
            $melon_streamings->_count = isset($melon_streamings->_count)? $melon_streamings->_count + $document->_count : $document->_count;
            $melon_streamings->Documents = isset($melon_streamings->Documents)? array_merge_recursive($melon_streamings->Documents, $document->Documents)
                : $document->Documents;
        }
        $params['streaming_url'] = json_encode($melon_streamings->Documents[0]);
        DB::table('test_push')
            ->where('app', $app)
            ->where('id', $request->input('id'))
            ->update($params);

        return redirect('/push/form/'.$request->input('id').'/test');
    }

    // Push 발송 취소
    public function delete(Request $request)
    {
        $app = Session::get('app', Auth::user()->app);
        $params = [
            'check_itmes' => $request->input('check_item')
        ];

        DB::table('test_push')
            ->where('app', $app)
            ->where('state', 'R')
            ->whereIn('id', $params['check_itmes'])
            ->update([
                'state' => 'X'
            ]);

        return redirect('/push/test');
    }
}

