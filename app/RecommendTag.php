<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RecommendTag
 *
 * @property int $id
 * @property string $name 추천 태그
 * @property string|null $app 앱 이름
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RecommendTag whereName($value)
 * @mixin \Eloquent
 */
class RecommendTag extends Model
{
    protected $fillable = [
        'name',
        'app'
    ];
    public $timestamps = false;
}
