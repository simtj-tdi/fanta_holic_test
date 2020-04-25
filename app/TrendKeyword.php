<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TrendKeyword
 *
 * @property int $id
 * @property string $app
 * @property string $keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TrendKeywordStats[] $stats
 * @property-read int|null $stats_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeyword whereUpdatedAt($value)
 * @mixin \Eloquent
 */

//todo 관리자화면 (껍데기)
class TrendKeyword extends Model
{
    protected $fillable = [
        'app',
        'keyword'
    ];


    public function stats()
    {
        return $this->hasMany('App\TrendKeywordStats', 'keyword', 'keyword');
    }
}
