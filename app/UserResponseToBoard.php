<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserResponseToBoard
 *
 * @property int $id
 * @property int $user_id 유저 id
 * @property int $board_id 게시물 id
 * @property int $response 유저 반응 1:좋아요 0 싫어요
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $app 앱 구분자
 * @property-read \App\Board $board
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToBoard whereUserId($value)
 * @mixin \Eloquent
 */
class UserResponseToBoard extends Model
{
    protected $fillable = [
        'app',
        'user_id',
        'board_id',
        'response'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function board(){
        return $this->belongsTo(Board::class);
    }
}
