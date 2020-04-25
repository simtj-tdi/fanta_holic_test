<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Lib\LobbyClassv6;
use App\Lib\Response;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lib\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller as baseController;

class Controller extends baseController
{
    protected $response;
    protected $redis;

    public function __construct()
    {
        $this->response = new Response();
        $this->redis = app('redis');
        $this->cache = app('cache');
    }

    //스케줄 리스트
    public function schedule_list_v2(Request $request){
        try {
            $validator = $this->validate($request, [
                'app'           =>  'required|string',
                'date'          =>  'required',   // 요청 날짜
                'timezone'      =>  'required|string'
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

        $user = Auth('api')->user();
        $params = [
            'app'   =>  $request->input('app'),
            'start_date'  =>  Carbon::createFromFormat('Y-m-d',$request->input('date'),$request->input('timezone'))
                ->startOfDay()
                ->setTimezone('UTC')
        ];
        $params['end_date'] = Carbon::createFromFormat('Y-m-d',$request->input('date'),$request->input('timezone'))
            ->endOfDay()
            ->setTimezone('UTC');

        $schedules = Schedule::where('scheduled_at','>=',$params['start_date'])
            ->where('scheduled_at','<',$params['end_date'])
            ->get();

        if($schedules->count() == 0){
            return $this->response->set_response(-2001,null);
        }

        $i = 0;
        foreach($schedules as $schedule){
            if($i == 0){
                $schedule -> date = Carbon::createFromFormat('Y-m-d',$request->input('date'),$request->input('timezone'))->format('d M');
            }else{
                $schedule -> date = '';
            }
            $before_parsing_schedules[] = $schedule;
            $i++;
        }

        $lobbyClass =new LobbyClassv6();
        $result_schedule    =   $lobbyClass->schedule_parsing($before_parsing_schedules,$user,$request->input('timezone'));
        $result['schedules'] = $result_schedule;

        return $this->response->set_response(0,$result);

    }


    //달력에 일정있는 날 표시용 날짜 timestamp 배열 시간대에 맞춰서
    public function schedule_check(Request $request){
        try {
            $validator = $this->validate($request, [
                'app'           =>  'required|string',
                'month'          =>  'required',   // 요청 날짜
                'timezone'  =>  'required|string'  //시간대
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

        $user = Auth('api')->user();

        $params = [
            'app'   =>  $request->input('app'),
            'month'  =>  Carbon::createFromFormat('Y-m',$request->input('month'),$request->input('timezone'))
                ->setTimezone('UTC'),
        ];

        //Asia/Seoul => +09:00
        $timezone_int_type = (new Carbon($request->input('timezone')))->format('P');

        //group by date with timezone
        $schedule_dates = Schedule::select(
            DB::raw("scheduled_at,DATE(CONVERT_TZ(scheduled_at,'+0:00','{$timezone_int_type}')) as date")
        )
            ->whereYear('scheduled_at',$params['month']->year)
            ->whereMonth('scheduled_at',$params['month']->month)
            ->orderBy('scheduled_at')
            ->groupBy('date')
            ->get()
            ->map(function($val) {
                return Carbon::createFromTimeString($val->scheduled_at)->timestamp;
            });

        if($schedule_dates->count() == 0){
            return $this->response->set_response(-2001,null);
        }

        $result['exist_schedule_dates'] = $schedule_dates;

        return $this->response->set_response(0,$result);
    }
}
