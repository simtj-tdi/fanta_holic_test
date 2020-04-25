<?php

namespace App;

use App\Enums\UserScoreLogType;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;


/**
 * App\UserScoreLog
 *
 * @property \App\Enums\UserScoreLogType|null $type
 * @property int $id
 * @property string $app 앱이름
 * @property int $user_id 유저 id
 * @property int $score 점수
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $music_id type = S 스트리밍 일때 music_id 값
 * @property int|null $board_id type = B 게시물 작성일때 board_id 값
 * @property int|null $item_board_id type = I 아이템 사용 일때 board_id 값
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereItemBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereMusicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserScoreLog whereUserId($value)
 * @mixin \Eloquent
 */
class UserScoreLog extends Model
{
    use CastsEnums;

    const FAN_FEED_BOARD_REGISTER_SCORE = 20; // 팬피드 작성
    const MUSIC_EXECUTE_SCORE = 5; //음악 1회 듣기
    const HEART_USE50_SCORE = 1; // 하트 50개 사용
    const FAN_SIGN_UP_SCORE = 300;  //회원가입
    const FAN_DAY_ATTENDANCE_SCORE  = 50; //출석

    protected $enumCasts = [
        'type' => UserScoreLogType::class
    ];

    protected $casts = [
        'type' => 'string'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'app',
        'user_id',
        'score',
        'type',
        'music_id',
        'board_id',
        'item_board_id',
        'created_at',
        'updated_at'
    ];
}
