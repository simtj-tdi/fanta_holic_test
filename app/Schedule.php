<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Schedule
 *
 * @property int $id
 * @property string $app 앱 이름
 * @property string|null $title 스케줄 제목
 * @property string|null $contents 스케줄 내용
 * @property string $scheduled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    protected $fillable = [
        'app',
        'title',
        'contents',
        'scheduled_at'
    ];
}
