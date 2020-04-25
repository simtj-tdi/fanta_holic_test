<?php

namespace App\Http\Controllers\FanX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrendController extends Controller
{
    public function keywordShow(){
        return view('fanx.trend.keyword');
    }
}
