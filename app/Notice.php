<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'app',
        'thumbnail_url',
        'type',
        'managed_type',
        'user_id',
        'title',
        'contents',
        'data'
    ];
}
