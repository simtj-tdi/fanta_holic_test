<?php

namespace App\Http\Controllers\Api\Log;

use App\Lib\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Lib\Util;
use Carbon\Carbon;

class Controller extends BaseController
{
    protected $response;
    protected $redis;

    public function __construct()
    {
        $this->response = new Response();
    }

    // 마켓 레퍼러 저장
    public function referrer(Request $request)
    {
        $validator = $this->validate($request, [
            'store_type' => 'required|string',
            'device' => 'required|string',
            'os_version' => 'required|string',
            'app_version' => 'required|string',
            'referrer' => 'required|string',
        ]);

        $params = [
            'store_type' => $request->input('store_type'),
            'device' => $request->input('device'),
            'os_version' => $request->input('os_version'),
            'app_version' => $request->input('app_version'),
            'referrer' => urldecode($request->input('referrer'))
        ];

//        try{
////            $refer = base64_decode($params['referrer']);
////            parse_str($refer,$output);
////            $params['invite_id'] = $output('invite_id');
//            parse_str($params['referrer'],$output);
//            if($output['utm_content'] == 'invite_id'){
//                $params['invite_id'] = $output['utm_campaign'];
//            }
//        }catch(\Exception $e){
//            // invited_id 가 referrer 에 없는 겨웅 기존 referrer 로 처리
//        }

        // UtilClass Definition
        $util = new Util();
        // Save ES Log (Redis Cache)
        $this->redis = app('redis');
        $this->redis->rpush('history:referrer', json_encode([
            'referrer' => $params['referrer'],
            'store_type' => $params['store_type'],
            'device' => $params['device'],
            'os_version' => $params['os_version'],
            'app_version' => $params['app_version'],
            //'ip' => app('geoip')->getIP(),
            'ip' => $util->getIpFromProxy($request),
            'reg_date' => Carbon::now('Asia/Seoul')->toDateTimeString()
        ]));

//        //레퍼러에 초대한사람 id가 있는 경우 초대한사람에게 push 알람 + 눈꽃 보상
//        if(isset($params['invite_id'])){
//            //앱 눈꽃 충전소 중에 친구초대있는지 확인
//            $this->documentdb = new AzureDocumentDB(env('AZURE_COSMOS_SQL_ENDPOINT'), env('AZURE_COSMOS_SQL_KEY'), false);
//            $this->documentdb->get('database')->select(env('AZURE_COSMOS_SQL_DB'));
//            $this->documentdb->get('collection')->select('ads');
//            $docs = $this->documentdb->get('document')->partition_ranges('ads');
//
//            $standard = new \stdClass;
//            foreach ($docs->PartitionKeyRanges as $partion) {
//                $document = $this->documentdb->get('document')
//                    ->query_option("SELECT TOP 1 *
//                            FROM doc
//                            WHERE doc.app='{$app}' AND doc.event_type='F'",
//                        array(),
//                        [
//                            'x-ms-max-item-count: 1',
//                            'x-ms-documentdb-query-enablecrosspartition: True',
//                            'x-ms-documentdb-partitionkeyrangeid: '.$docs->_rid.','.$partion->id
//                        ]);
//
//                $standard->_rid = $document->_rid;
//                $standard->_count = isset($standard->_count)? $standard->_count + $document->_count : $document->_count;
//                $standard->Documents = isset($standard->Documents)? array_merge_recursive($standard->Documents, $document->Documents)
//                    : $document->Documents;
//            }
//            //눈꽃 충전소관리에 친구초대가 없는경우 예외처리
//            if($standard->_count >0){
//                //초대 보상 눈꽃 개수 확인
//                $standard = $standard->Documents[0];
//                $snow_count = isset($standard->snow_count) ? $standard->snow_count : 0;
//
//                //눈꽃 지급
//                $usermanagement= new Usermanagement();
//                $usermanagement->addsnow($app,$params['invite_id'],$snow_count);
//
//                //push 알람
//                $this->db = app('db');
//                $this->config = app('config')['celeb'][$app];
//                $this->db->table('push')->insert([
//                    'app' => $app,
//                    'batch_type' => 'P',
//                    'managed_type' => 'F',
//                    'user_id' => (int)$params['invite_id'],
//                    'title' => $standard->push_title,
//                    'contents' => $standard->push_message,
//                    'tick' =>  $standard->push_message,
//                    'push_type' => 'T',
//                    'action' => 'A',
//                    'start_date' => Carbon::now(),
//                ]);
//            }
//        }
        return $this->response->set_response(0, null);
    }
}
