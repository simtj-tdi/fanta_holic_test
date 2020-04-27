<?php

namespace App\Console\Commands;

use App\Board;
use App\Follow;
use App\User;
use App\Device;
use Carbon\Carbon;
use App\Push;
use Carbon\CarbonTimeZone;
use Illuminate\Console\Command;
use App\Http\Traits\PushTrait;
use Illuminate\Support\Facades\DB;


class PushWorkerCommand extends Command
{
    use PushTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:worker {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Worker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $type = strtoupper($this->argument('type'));

        $apps = array_keys(config('celeb'));
        // 전체 발송
        foreach($apps as $app){

            if ($type == 'A') {
                $this->all($app);
            }

            // 개별 발송
            elseif ($type == 'P') {
                $this->person($app);
            }

            // 새 게시물
            elseif ($type == 'N') {
                $this->new_content($app);
            }

//            elseif (in_array($type, $this->TIMEZONE)) {
//                $this->byCountry($app, $type);
//            }
            elseif ($type == "COUNTRY") {
                //$users = User::distinct()->get();
                $users = User::select('timezone')->distinct('timezone')->where('timezone','!=','UTC')->get();
                foreach ($users as $user) {
                    $Tz = Carbon::now($user->timezone);
                    //echo $Tz->format('Y-m-d H:i');
                    //$Tz_Format = "20";
                    $Tz_Format = $Tz->format('H');
                    if ($Tz_Format == "13" || $Tz_Format == "20" || $Tz_Format == "22") {
                        $this->byCountry($app, $user->timezone);
                    }

                    sleep(2);
                }
            }


        }
    }

    //todo 모델 함수처리
    protected function all($app){
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
            ->where('start_date', '<', Carbon::now()->toDateTimeString())
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
                ->where(function($query) use($push){
                    if($push->manage_type == 'S'){   // push 생성시 입력한 managed_type 기준으로 devices에 있는 push 받기/안받기 상태 종류 검사
                        return $query->where('streaming_push',1);
                    }elseif ($push->managed_type == 'C'){
                        return $query->where('comment_push',1);
                    }elseif ($push->managed_type == 'B'){
                        return $query->where('board_push',1);
                    }else{
                        return $query->where('is_push',1);
                    }
                })
                //->where('user_id',$push->user_id)
                ->where('fcm_token','!=',null)
                ->groupBy('fcm_token');
            $count = $query->count();


            $loop = (int) ceil($count / $limit);

            Push::where('id', $push->id)
                ->update([
                    'state' => 'S'
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
                                'message' => $push->content,
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
    }

    protected function person($app){
        $items = Push::select([
            'pushes.id',
            'pushes.batch_type',
            'pushes.title',
            'pushes.content',
            'pushes.tick',
            'pushes.push_type',
            'pushes.img_url',
            'pushes.action',
            'pushes.url',
            'pushes.board_type',
            'pushes.board_id',
            'pushes.streaming_url',
            'devices.store_type',
            'devices.fcm_token'
        ])
            ->rightJoin('devices', function ($join) {
                $join->on('pushes.app', '=', 'devices.app')
                    ->on('pushes.user_id', '=', 'devices.user_id');
            })
            ->where('pushes.app', $app)
            // 발송 타입
            ->where('pushes.batch_type', 'P')
            // 진행 상태
            ->where('pushes.state', 'R')
            // 시작일
            ->where('pushes.start_date', '<', Carbon::now()->toDateTimeString())
            // 푸시 on/off
            ->where(function($query){
                $query->where(function($query){
                    $query->where('pushes.managed_type','S')->where('devices.streaming_push',1);
                })->orwhere(function($query){
                    $query->where('pushes.managed_type','C')->where('devices.comment_push',1);
                })->orwhere(function($query){
                    $query->where('pushes.managed_type','B')->where('devices.board_push',1);
                })->orwhere(function($query){
                    $query->whereNotIn('managed_type',['S','C','B'])->where('devices.is_push',1);
                });
            })
            ->where('devices.fcm_token','!=',null)
            ->orderBy('pushes.id')
            // 발송 개 수
            ->limit(1000)
            ->get()
            ->keyBy('id')
            ->transform(function ($item) {
                $data = [
                    'registration_ids' => [$item->fcm_token],
                    'data' => [
                        'id' => $item->id,
                        'batch_type' => $item->batch_type,
                        'title' => $item->title,
                        'message' => $item->content,
                        'tick' => $item->tick,
                        'push_type' => $item->push_type,
                        'push_type_sub' => [
                            'img_url' => ($item->push_type == 'I') ? $item->img_url : ''
                        ],
                        'action' => $item->action,
                        'action_sub' => [
                            'url' => ($item->action == 'M') ? $item->url : '',
                            'type' => ($item->action == 'B') ? $item->board_type : '',
                            'board_id' => ($item->action == 'B') ? $item->board_id : '',
                            'campaign' =>  ($item->action== 'S')   ?   json_decode($item->streaming_url) : ''
                        ]
                    ]
                ];
                if ($item->store_type == 'ios') {
                    $data = array_merge($data, [
                        'notification' => [
                            'title' => $item->title,
                            'body' => $item->contents,
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]);
                }

                return $data;
            });

//        dump('보냄');
//        dump($items);
        // 상태 변경 (발송 중)
        Push::whereIn('id', $items->keys())
            ->update(['state' => 'S']);

        // 푸시 발송
        $results = collect($this->sender($app, $items));
//        dump('결과');
//        dump($results);
        // 상태 변경 (완료)
        // 성공
        Push::whereIn('id', $results->where('success', 1)->keys())
            ->update([
                'state' => 'Y',
                'success' => 1
            ]);


        // 실패
        Push::whereIn('id', $results->where('failure', 1)->keys())
            ->update([
                'state' => 'Y',
                'fail' => 1
            ]);
    }

    //todo 모델 함수처리
    protected function new_content($app){

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
            ->where('batch_type', 'N')
            // 진행 상태
            ->where('state', 'R')
            // 시작일
            ->where('start_date', '<', Carbon::now()->toDateTimeString())
            ->orderBy('id')
            ->limit(1)
            ->first();

        $artist_arr = Board::select('artists_id')
            ->where('created_at', '>',  Carbon::now()->addHour(-1))
            ->groupBy('artists_id')
            ->havingRaw('count(*) > 0')
            ->get()
        ;

        $user_id_arr = follow::select('user_id')
            ->whereIn('artist_id', $artist_arr)
            ->get()
        ;

        // 대기 중 목록이 있을 경우
        if ( ! is_null($push)){
            // 발송 개 수
            $limit = 1000;
            $query = Device::whereIn('user_id',$user_id_arr)
                ->where('devices.is_push',1)
                ->where('devices.fcm_token','!=',null)
                ->groupBy('devices.fcm_token');
            $count = $query->count();

            $loop = (int) ceil($count / $limit);

            Push::where('id', $push->id)
                ->update([
                    'state' => 'S'
                ]);

            $success = $fail = 0;
            for ($i = 0; $i < $loop; $i++) {
                $offset = $i * $limit;
                $items = $query->select(['user_id', 'store_type', 'fcm_token'])
                    ->skip($offset)
                    ->take($limit)
                    ->get()
                    ->keyBy('user_id')
                    ->transform(function ($item) use ($push) {
                        $data = [
                            'registration_ids' => [$item->fcm_token],
                            'data' => [
                                'id' => $push->id,
                                'user_id'   =>  $item->user_id,
                                'batch_type' => $push->batch_type,
                                'title' => $push->title,
                                'message' => $push->content,
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
    }

    protected function byCountry($app, $type)
    {
        // push table insert
        $pushTxst = $this->getLanguage($type);

        $pushId = Push::create([
            'app' => $app,
            'batch_type' => 'N',
            'managed_type' => 'M',
            'user_id' => 0,
            'title' => $pushTxst,
            'content' => $pushTxst,
            'tick' => 0,
            'push_type' => 'T',
            'action' => 'A',
            'state' => 'R',
            'start_date' => Carbon::now(),
        ]);
        // echo $pushId->id;

        if (!is_null($pushId)) {

            // user table select
            $deviceArr = Device::select([
                'devices.id',
                'devices.user_id',
                'devices.store_type',
                'devices.os_version',
                'devices.app_version',
                'devices.fcm_token',
                'devices.device_key',
                'devices.is_push',
                'devices.app',
                'devices.streaming_push',
                'devices.comment_push',
                'devices.board_push',
                'users.timezone'
            ])
                ->leftJoin('users', function ($join){
                    $join->on('devices.user_id', '=', 'users.id');
                })
                ->where (function ($query) use ($pushId) {
                    if($pushId->manage_type == 'S') {
                        return $query->where('devices.streaming_push',1);
                    } elseif ($pushId->managed_type == 'C'){
                        return $query->where('devices.comment_push',1);
                    } elseif ($pushId->managed_type == 'B'){
                        return $query->where('devices.board_push',1);
                    } else {
                        return $query->where('devices.is_push',1);
                    }
                })
                ->where('users.timezone','=', $type)
                ->where('devices.fcm_token', '!=', null)
                ->groupBy('devices.fcm_token');

            $count = $deviceArr->count();

            // 발송 개 수
            $limit = 1000;

            $loop = (int) ceil($count / $limit);

            Push::where('id', $pushId->id)
                ->update([
                    'state' => 'S'
                ]);
            $success = $fail = 0;

            for ($i = 0; $i < $loop; $i++) {
                $offset = $i * $limit;
                // push data parsing
                $items = $deviceArr->select(['user_id', 'store_type', 'fcm_token'])
                    ->skip($offset)
                    ->take($limit)
                    ->get()
                    ->keyBy('user_id')
                    ->transform(function ($item) use ($pushId) {
                        $data = [
                            'registration_ids' => [$item->fcm_token],
                            'data' => [
                                'id' => $pushId->id,
                                'user_id'   =>  $item->user_id,
                                'batch_type' => $pushId->batch_type,
                                'title' => $pushId->title,
                                'message' => $pushId->content,
                                'tick' => $pushId->tick,
                                'push_type' => $pushId->push_type,
                                'push_type_sub' => [
                                    'img_url' => ($pushId->push_type == 'I') ? $pushId->img_url : ''
                                ],
                                'action' => $pushId->action,
                                'action_sub' => [
                                    'url' => ($pushId->action == 'M') ? $pushId->url : '',
                                    'type' => ($pushId->action == 'B') ? $pushId->board_type : '',
                                    'board_id' => ($pushId->action == 'B') ? $pushId->board_id : '',
                                    'cdn_url'   => ($pushId->action =='B') ? app('config')['celeb'][$pushId->app]['cdn'] : '',
                                    'campaign' =>  ($pushId->action== 'S')   ?   json_decode($pushId->streaming_url) : ''
                                ]
                            ]
                        ];

                        if ($item->store_type == 'ios') {
                            $data = array_merge($data, [
                                'notification' => [
                                    'title' => $pushId->title,
                                    'body' => $pushId->contents,
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

                // push send
                $results = collect($this->sender($app, $items));
//                dump('결과');
//                dump($results);
                $success += $results->sum('success');
                $fail += $results->sum('failure');

                sleep(1);
            }

            // 상태 변경 (완료)
            Push::where('id', $pushId->id)
                ->update([
                    'state' => 'Y',
                    'success' => $success,
                    'fail' => $fail
                ]);
        } else {
            dump('보낼 push가 없음');
        }

    }

    protected function getLanguage($type)
    {
        $CountryTxt = array(
            "Asia/Ho_Chi_Minh"=>"Bài đăng mới đã được đăng ký.(Ho_Chi_Minh)",
            "Asia/Seoul"=>"새로운 게시물이 등록되었습니다.(Seoul)",
        );

        if (empty($CountryTxt[$type])) {
            return "new posts have been registered.({$type})";
        }

        return $CountryTxt[$type];
    }
}
