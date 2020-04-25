<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Standard extends Model
{
    protected $fillable = [
        'app',
        'spamming',
        'spam_count',
        'blind_count',
        'black_count',
        'comment_like_score',
        'article_like_score',
        'comment_score',
        'login_reward',
        'ranking',   //todo 유저등급기획 폐지 -> 필요없는컬럼 삭제
        'item_point_count',
        'created_at',
        'updated_at'
    ];
}
