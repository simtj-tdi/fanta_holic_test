<?php

namespace App\Http\Controllers\Admin\Schedule;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Schedule;
use App\Lib\Log;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        //todo 관리자화면 timezone 설정  'Asia/Seoul' 부분 관리자화면 보는사람에 ㄴ맞춰서 수정
        $admin_timezone = 'Asia/Seoul';

        $params = [
            'page_cnt'  =>  $request->input('page_cnt',10),
            'start_date'=>Carbon::createFromFormat('Y-m-d H:i',
                $request->input('start_date',Carbon::now($admin_timezone)->addDays(-7)->startOfDay()->format('Y-m-d H:i'))
                ,$admin_timezone)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i'),
            'end_date'=>Carbon::createFromFormat('Y-m-d H:i',
                $request->input('end_date',Carbon::tomorrow($admin_timezone)->format('Y-m-d H:i')),
                $admin_timezone)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i'),
        ];

        $rows = Schedule::where('app',$app)
            ->whereBetween('scheduled_at',[$params['start_date'],$params['end_date']])
            ->orderBy('scheduled_at','desc')
            ->paginate($params['page_cnt'])
            ->map(function($val) use($admin_timezone){
                $val->scheduled_at = Carbon::createFromFormat('Y-m-d H:i:s',$val->scheduled_at,'UTC')
                    ->setTimezone($admin_timezone);
                return $val;
            });

        $params['start_date'] = Carbon::createFromFormat('Y-m-d H:i',$params['start_date'],'UTC')
            ->setTimezone($admin_timezone)->format('Y-m-d H:i');
        $params['end_date'] = Carbon::createFromFormat('Y-m-d H:i',$params['end_date'],'UTC')
            ->setTimezone($admin_timezone)->format('Y-m-d H:i');

        return view('schedule.index')->with([
            'rows' =>$rows,
            'params'    =>  $params,
            'search_count'  =>  $rows->count(),
            'total'     => Schedule::where('app',$app)->count()
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

        $params = [
            'app'   =>  $app,
            'scheduled_at'  =>  $request->input('create_scheduled_at',Carbon::now()),
            'title'         =>  $request->input('create_title'),
            'contents'      =>  $request->input('create_contents')
        ];
        $params['scheduled_at'] = Carbon::createFromFormat('Y-m-d H:i',$params['scheduled_at'],'Asia/Seoul')//todo 관리자화면 timezone 관리
            ->setTimezone('UTC');

        Schedule::create($params);

        return redirect('admin/schedules');
    }

    public function destroy(Request $request){
        dd('todo 스케줄 삭제');
    }

    public function edit(Request $request){
        dd('todo 스케줄 수정');
    }
}
