<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    //

    protected $fillable = [
        'board_id',
        'currentLanguage',
        'targetLanguage',
        'text'
    ];
}
