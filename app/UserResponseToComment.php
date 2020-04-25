<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserResponseToComment
 *
 * @property int $id
 * @property int $user_id 유저 id
 * @property int $comment_id 게시물 id
 * @property int $response 유저 반응 1:좋아요 0 싫어요
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Board $comment
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserResponseToComment whereUserId($value)
 * @mixin \Eloquent
 */
class UserResponseToComment extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'response'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comment(){
        return $this->belongsTo(Board::class);
    }
}
