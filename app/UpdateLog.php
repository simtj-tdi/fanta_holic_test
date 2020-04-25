<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UpdateLog
 *
 * @property int $id
 * @property int|null $board_id board_id
 * @property string|null $board_type board 종류 instagram,youtube,web,news
 * @property string|null $update_name update 종류 개시,내림,남자,여자,검수등록,검수해제,B태그수정,삭제
 * @property string|null $prev_tag 태그수정일시 기존B태그들
 * @property string|null $after_tag 태그수정일시 수정후B태그들
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereAfterTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereBoardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog wherePrevTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereUpdateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UpdateLog extends Model
{
    protected $fillable = [
        'board_id',
        'board_type',
        'update_name',
        'prev_tag',
         'after_tag'
    ];


}
