<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Comment
 *
 * @property int $id id
 * @property int|null $user_id 작성유저 id
 * @property int|null $board_id 게시글 id
 * @property string|null $comment 댓글내용
 * @property int|null $parent_id 부모댓글 id ,없으면 null
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $app 앱 구분자
 * @property string|null $type 댓글 타입 [N:일반, C:셀럽 ]
 * @property-read \App\Board $boards
 * @property-read mixed $dis_like_count
 * @property-read mixed $like_count
 * @property-read mixed $report_count
 * @property-read mixed $user_nickname
 * @property-read \App\Comment|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $replies
 * @property-read int|null $replies_count
 * @property-read \App\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserResponseToComment[] $userresponsetocomment
 * @property-read int|null $userresponsetocomment_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Comment withoutTrashed()
 * @mixin \Eloquent
 * @property-read mixed $user_profile_photo_url
 */
class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'board_id',
        'comment',
        'app',
        'type',
        'parent_id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['like_count', 'report_count', 'user_nickname'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function getLikeCountAttribute()
    {
        return (int)$this->userresponsetocomment()->where('response', 1)->count();
    }

    public function getDisLikeCountAttribute()
    {
        return (int)$this->userresponsetocomment()->where('response', 0)->count();
    }

    public function getReportCountAttribute()
    {
        return (int)$this->userresponsetocomment()->where('response', 0)->count();
    }

    public function getUserNicknameAttribute()
    {
        return (string)(isset($this->user->nickname) ? $this->user->nickname : '');
    }

    public function getUserProfilePhotoUrlAttribute()
    {
        return $this->user->profile_photo_url ??'';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userresponsetocomment()
    {
        return $this->hasMany(UserResponseToComment::class);
    }

    public function boards()
    {
        return $this->belongsTo(Board::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }
}
