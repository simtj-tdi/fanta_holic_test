<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    protected $fillable = [
        'user_id',
        'app',
        'user_id',
        'tags'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
