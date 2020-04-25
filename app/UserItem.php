<?php

namespace App;

use App\Enums\UserItemType;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UserItem
 *
 * @property int $id
 * @property string|null $app app 구분자
 * @property string|null $user_id 유저 id
 * @property int|null $item_count item 사용/충전 개수
 * @property int|null $board_id 게시물 아이템 사용시 게시물 id
 * @property string|null $log_type 게시물 아이템 사용:B
 * @property string|null $description item 사용/충전 경로
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereItemCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserItem whereUserId($value)
 * @mixin \Eloquent
 */
class UserItem extends Model
{
    use CastsEnums;


    protected $fillable = [
        'app',
        'user_id',
        'item_count',
        'board_id',
        'log_type',
        'description'
    ];


    protected $enumCasts = [
        'log_type' => UserItemType::class
    ];

    protected $casts = [
        'log_type' => 'string'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
