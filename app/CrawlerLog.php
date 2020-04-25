<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawlerLog extends Model
{
    protected $fillable = ['status', 'crawler_cnt'];
}
