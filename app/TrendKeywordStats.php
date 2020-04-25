<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TrendKeywordStats
 *
 * @property int $id
 * @property string $type
 * @property string $keyword
 * @property string $date
 * @property int $pc_count
 * @property int $mobile_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereMobileCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats wherePcCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TrendKeywordStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */

//todo 관리자화면 (껍데기)
class TrendKeywordStats extends Model
{
    protected $fillable = [
        'type',
        'keyword',
        'date',
        'pc_count',
        'mobile_count',
    ];
}
