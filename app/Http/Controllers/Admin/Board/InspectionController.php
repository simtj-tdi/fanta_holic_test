<?php

namespace App\Http\Controllers\Admin\Board;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class InspectionController extends Controller
{
    public function index(Request $request){
        $params = [
            'board' =>  $request->input('board'),
            'start_date' => $request->input('start_date','2019-01-15'),
            'end_date' => $request->input('end_date',Carbon::now()->toDateString()),
            'state' => $request->input('state'),
            'gender' => $request->input('gender', 1),
        ];


        return view('inspection.index')->with([
            'title' => 'inspection',
            'Inspection_menu' =>    'active',
//            'params' => $params,
//            'tag_list'  =>  $tags,
//            'rows' => $rows,
//            'total' => $total_cnt
        ]);
    }
}
