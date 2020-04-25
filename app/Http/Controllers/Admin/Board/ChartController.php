<?php

namespace App\Http\Controllers\Admin\Board;

use App\Board;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Controller as BaseController;

class ChartController extends BaseController
{
    public function index(Request $request)
    {
        $params = [
            'type' => $request->input('type','youtube'),
            'start_date' => $request->input('start_date',Carbon::now()->addDAys(-7)->toDateString()),
            'end_date' => $request->input('end_date',Carbon::yesterday()->toDateString()),
            'range' =>  $request->input('range','d')
        ];

        return view('data.index')->with([
            'title' =>  'daily data amount',
            'chart_menu'=>'active',
            'params'    =>  $params
        ]);
    }

    public function data(Request $request)
    {
        $params = [
            'type' => $request->input('type'),
            'start_date' => $request->input('start_date',Carbon::now()->addDAys(-7)->toDateString()),
            'end_date' => $request->input('end_date',Carbon::yesterday()->toDateString()),
            'range' =>  $request->input('range','d')
        ];

        //시작일 끝일 사이 배열
        //1더하는 이유 막대그래프로 볼때 마지막 날 데이터가 가긴하는대 짤려서 안 보이는거 방지
        $date1= $params['start_date'];
        $date2= $params['end_date'];

        if($params['range']=='d')
        {
            $date[] = $date1;
            $date1 = date("Y-m-d", strtotime($date1. " + 1 day"));    //일단 date1에 1일 더하고
            while($date1 <= $date2)
            {    //date1이 date2보다 작으면
                $date[] = $date1;    //배열에 date1 추가
                $date1 = date("Y-m-d", strtotime($date1. " + 1 day"));  //date1에 1일 더함
            }
        }


        elseif($params['range']=='m')
        {
            $date1=DateTime::createFromFormat('Y-m-d',$date1)->format('Y-m');
            $date2=DateTime::createFromFormat('Y-m-d',$date2)->format('Y-m');
            $date[] = $date1;
            $date1 = date("Y-m", strtotime($date1. " + 1 month"));    //일단 date1에 1일 더하고
            while($date1 <= $date2)
            {    //date1이 date2보다 작으면
                $date[] = $date1;    //배열에 date1 추가
                $date1 = date("Y-m", strtotime($date1. " + 1 month"));  //date1에 1일 더함
            }
        }

        elseif($params['range']=='y')
        {
            $date1=DateTime::createFromFormat('Y-m-d',$date1)->format('Y');
            $date2=DateTime::createFromFormat('Y-m-d',$date2)->format('Y');
            $date=range($date1,$date2);
            $date=array_map(function($value){
                return (string)$value;
            },$date);
        }

        $starttime = Carbon::createFromTimeString($params['start_date'].' 00:00:00');
        $endtime = Carbon::createFromTimeString($params['end_date'].' 23:59:59');


        if($params['type'] == null)
        {  //(타입미지정 => 기간만) ===>  (=>전부다)
            $rows = Board::when($params['range'],function($query) use($params)
                {
                    if ($params['range'] == 'd')
                    {
                        return $query->select(DB::raw("type,date_format(recorded_at,'%Y-%m-%d') as date,count(*) as count"));
                    }
                    elseif ($params['range'] == 'm')
                    {
                        return $query->select(DB::raw("type,date_format(recorded_at,'%Y-%m') as date,count(*) as count"));
                    }
                    else
                    {
                        return $query->select(DB::raw("type,date_format(recorded_at,'%Y') as date,count(*) as count"));
                    }
                })
                ->whereBetween('recorded_at', [$starttime, $endtime])
                ->groupby("date")
                ->groupby("type")
                ->get();
            $types = ['youtube','instagram','web','news'];

            //타입별로 저장
            foreach($types as $type)
            {
                foreach($rows as $row)
                {
                    if($row->type==$type)
                    {
                        $exist_date[$type][]=$row->date;
                        $data[$type][]=[
                            'date'  =>  $row->date,
                            'count' =>  $row->count
                        ];
                    }
                }
            }

            //범위내에 값이 하나도 없는경우 null값 예외처리
            foreach($types as $type)
            {
                if(!isset($exist_date[$type]))
                {
                    $data[$type]=[];
                    $exist_date[$type]=[];
                }
            }

            //타입별로 없는 날짜에 count=0 저장
            foreach($types as $type)
            {
                foreach($date as $unit)
                {
                    if (!in_array($unit, $exist_date[$type]))
                    {
                        $data[$type][] = [
                            'date' => $unit,
                            'count' => 0
                        ];
                    }
                }
            }

            //타입별로 정렬
            foreach($types as $type)
            {
                usort($data[$type], function($a,$b)
                {
                    if((int)str_replace('-','',$a['date']) == (int)str_replace('-','',$b['date']))
                    {
                        return 0;
                    }
                    return ((int)str_replace('-','',$a['date']) < (int)str_replace('-','',$b['date'])) ? -1 : 1;
                });
            }
            return json_encode([
                'date'  => $date,
                'data' =>$data
            ]);
        }
        else
        {
            $rows = Board::when($params['range'],function($query) use($params)
                {
                    if ($params['range'] == 'd') {
                        return $query->select(DB::raw("date_format(recorded_at,'%Y-%m-%d') as date,count(*) as count"));
                    } elseif ($params['range'] == 'm') {
                        return $query->select(DB::raw("date_format(recorded_at,'%Y-%m') as date,count(*) as count"));
                    } else {
                        return $query->select(DB::raw("date_format(recorded_at,'%Y') as date,count(*) as count"));
                    }
                })
                ->where('type',$params['type'])
                ->whereBetween('recorded_at', [$starttime, $endtime])
                ->groupby("date")
                ->get();

            foreach($rows as $row)
            {
                $exist_time[]=$row->date;
                $data[]=[
                    'date'=>$row->date,
                    'count'=>$row->count
                ];
            }
            foreach($date as $unit)
            {
                if (!in_array($unit, $exist_time))
                {
                    $data[] = [
                        'date' => $unit,
                        'count' => 0
                    ];
                }
            }

            usort($data, function($a,$b)
            {
                return (int)$a['date'] - (int)$b['date'];
            });

            return json_encode([
                'rows'=>$data,
                'type'=>$params['type']
            ]);
        }
    }

}
