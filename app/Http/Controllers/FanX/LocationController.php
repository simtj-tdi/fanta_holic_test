<?php

namespace App\Http\Controllers\FanX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{

    public function stalkerShow(){
        return view('fanx.location.stalker');
    }

    public function fanShow(){
        return view('fanx.location.fan')->with([
            'searchDate' => now()->subDays(7)->toDateString()
        ]);
    }
}
