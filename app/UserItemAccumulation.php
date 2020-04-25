<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserItemAccumulation
 *
 * @property int $id
 * @property string|null $app 앱이름
 * @property int|null $user_id 유저 id
 * @property int|null $item_count 누적 아이템 사용수 기준개수 달성시 점수 지급후 0으로 초기화
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereItemCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItemAccumulation whereUserId($value)
 * @mixin \Eloquent
 */
class UserItemAccumulation extends Model
{
    protected $fillable = [
        'app',
        'user_id',
        'item_count'
    ];
}
