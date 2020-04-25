<?php

namespace App\Http\Controllers\FanX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function installedShow(){
        return view('fanx.app.installed');
    }

    public function uninstalledShow(){
        return view('fanx.app.uninstalled');
    }

    public function usedShow(){
        return view('fanx.app.used');
    }

}
