<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Lib\Response;
use App\Device;
use App\Push;
use App\Http\Traits\PushTrait;
use App\Http\Controllers\Controller;

class TempController extends Controller
{
    protected $response;
    use PushTrait;
    public function push_generator(){
        $this->response = new Response();
        $app='jihoon';

        Push::create([
            'app' => 'jihoon',
            'batch_type' =>'A',
            'managed_type' =>'M',
            'title' => '박지훈 L.O.V.E 전체듣기',
            'content' => '박지훈 L.O.V.E ',
            'tick' => '박지훈 L.O.V.E ♥',
            'push_type' => 'T',
            'action' => 'S',
            'board_type' =>'vlive',
            'streaming_url' => "{\"id\":1,\"app\":\"jihoon\",\"hashtag\":null,\"title\":\"\ubc15\uc9c0\ud6c8 L.O.V.E\",\"event_type\":\"M\",\"order_num\":1,\"img_url\":\"\/images\/ads\/1564133705_154994623722862.jpg\",\"repeat\":60,\"description\":\"\uc804\uccb4\uac10\uc0c1 \ud6c4 \uc9c0\uae09\",\"url\":\"intent:\/\/play?cid=31694453&ctype=1&openplayer=Y&launchedby=kakao&ref=kakao&contsid=31694453#Intent;scheme=melonapp;package=com.iloen.melon;end\",\"app_package\":\"com.iloen.melon\",\"item_count\":20,\"push_title\":\"\ubc15\uc9c0\ud6c8 L.O.V.E \ub4e3\uae30 \uc644\ub8cc!\",\"psuh_message\":\"\ud558\ud2b8 20\uac1c\uac00 \uc9c0\uae09 \ub418\uc5c8\uc2b5\ub2c8\ub2e4\",\"state\":1,\"start_date\":\"2019-09-04 11:16:23\",\"end_date\":\"2019-12-04 11:16:23\",\"thumbnail_1_1\":null,\"thumbnail_2_1\":null,\"thumbnail_3_1\":null,\"thumbnail_1_2\":null,\"thumbnail_2_2\":null,\"thumbnail_3_3\":null,\"deleted_at\":null,\"created_at\":\"2019-09-04 11:16:23\",\"updated_at\":\"2019-09-04 11:16:23\",\"push_tick\":\"20\"}",
            'state' => "R",
            'campaign_id'   =>1
        ]);

        $push = Push::select([
            'id',
            'app',
            'batch_type',
            'managed_type',
            'new_post_count',
            'title',
            'content',
            'tick',
            'push_type',
            'img_url',
            'action',
            'url',
            'board_type',
            'board_id',
            'streaming_url',
            'state'
        ])
            //앱
            ->where('app',$app)
            //발송 타입
            // 발송 타입
            ->where('batch_type', 'A')
            // 진행 상태
            ->where('state', 'R')
            // 시작일
            ->where('start_date', '<', Carbon::now()->addHours(1)->toDateTimeString())
            ->orderBy('id')
            ->limit(1)
            ->first();

        if($push != null &&$push->action == 'B'){
            $board = Board::where('id',$push->board_id)->get()->last();
            $board->created_at_timestamp = (string)(Carbon::createFromTimeString($board['created_at'])->timestamp);
        }else{
            $board = '';
        }

        // 대기 중 목록이 있을 경우
        if ( ! is_null($push)) {
            // 발송 개 수
            $limit = 1000;

            $query = Device::where('app',$app)
                ->where('is_push',1)
                ->where('fcm_token','!=',null)
                ->groupBy('fcm_token');
            $count = $query->count();

            $loop = (int) ceil($count / $limit);

            $push->update([
                'state'=>'S'
            ]);

            $success = $fail = 0;
            for ($i = 0; $i < $loop; $i++) {
                $offset = $i * $limit;
                $items = $query->select(['user_id', 'store_type', 'fcm_token'])
                    ->skip($offset)
                    ->take($limit)
                    ->get()
                    ->keyBy('user_id')
                    ->transform(function ($item) use ($push,$board) {
                        $data = [
                            'registration_ids' => [$item->fcm_token],
                            'data' => [
                                'id' => $push->id,
                                'user_id'   =>  $item->user_id,
                                'batch_type' => $push->batch_type,
                                'title' => $push->title,
                                'message' => $push->contents,
                                'tick' => $push->tick,
                                'push_type' => $push->push_type,
                                'push_type_sub' => [
                                    'img_url' => ($push->push_type == 'I') ? $push->img_url : ''
                                ],
                                'action' => $push->action,
                                'action_sub' => [
                                    'url' => ($push->action == 'M') ? $push->url : '',
                                    'type' => ($push->action == 'B') ? $push->board_type : '',
                                    'board_id' => ($push->action == 'B') ? $push->board_id : '',
                                    'cdn_url'   => ($push->action =='B') ? app('config')['celeb'][$push->app]['cdn'] : '',
                                    'board'   => ($push->action =='B') ? json_encode($board) : '',
                                    'campaign' =>  ($push->action== 'S')   ?   json_decode($push->streaming_url) : ''
                                ]
                            ]
                        ];


                        if ($item->store_type == 'ios') {
                            $data = array_merge($data, [
                                'notification' => [
                                    'title' => $push->title,
                                    'body' => $push->contents,
                                    'sound' => 'default',
                                    'badge' => 1
                                ]
                            ]);
                        }
                        return $data;
                    });
//                dump('보냄');
//                dump($items);
                // 푸시 발송
                $results = collect($this->sender($app, $items));
//                dump('결과');
//                dump($results);
                $success += $results->sum('success');
                $fail += $results->sum('failure');

                sleep(1);
            }

            // 상태 변경 (완료)
            Push::where('id', $push->id)
                ->update([
                    'state' => 'Y',
                    'success' => $success,
                    'fail' => $fail
                ]);
        }else{
            dump('보낼 push가 없음');
        }

        return $this->response->set_response(0,null);
    }
}
