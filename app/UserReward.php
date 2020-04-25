<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserReward
 *
 * @property int $id
 * @property string $app 앱이름
 * @property int|null $campaign_id 캠페인 id
 * @property int|null $board_id 게시물 id
 * @property int $user_id 유저 id
 * @property string $log_type 게시물 작성:B ,인앱샵 구매:A, 출석보상:D, 캠페인 보상:C
 * @property string|null $description item 충전 경로 설명
 * @property int $item_count 지급한 아이템 개수
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereItemCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReward whereUserId($value)
 * @mixin \Eloquent
 */
class UserReward extends Model
{
    protected $fillable = [
        'app',
        'campaign_id',
        'board_id',
        'user_id',
        'log_type',
        'description',
        'item_count'
    ];
}
