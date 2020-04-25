<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tag
 *
 * @property int $id
 * @property string $name 태그
 * @property string|null $board 게시물 종류 [ youtube / instagram / news / web ]
 * @property string|null $type 태그 종류 [ ori / custom ]
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereBoard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereType($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $fillable = [
        'name',
        'board',
        'type',
    ];
    public $timestamps = false;

}
