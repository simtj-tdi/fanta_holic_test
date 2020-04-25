<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//todo 관리자화면 (껍데기)
class AppStats extends Model
{
    protected $fillable = [
        'app',
        'package',
        'status',
        'date',
        'count',
        'detail'
    ];
}
