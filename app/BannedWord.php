<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BannedWord
 *
 * @property int $id
 * @property string $name 금칙어
 * @property string|null $app 앱 이름
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BannedWord whereName($value)
 * @mixin \Eloquent
 */
class BannedWord extends Model
{
    protected $fillable = [
        'name',
        'app'
    ];

    public $timestamps = false;
}
