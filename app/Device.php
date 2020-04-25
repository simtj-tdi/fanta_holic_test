<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Device
 *
 * @property int $id
 * @property int $user_id 유저 id
 * @property string|null $device 핸드폰 기종
 * @property string|null $store_type 마켓 타입 and ios
 * @property string|null $os_version os version
 * @property string|null $app_version app version
 * @property string|null $fcm_token fcm_token
 * @property string|null $device_key 안드로이드 비회원 로그인용 키
 * @property int $is_push 알람 받으면 true 안받으면 false
 * @property string|null $app 앱 구분자
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereDeviceKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereIsPush($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereStoreType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereUserId($value)
 * @mixin \Eloquent
 * @property int $streaming_push 스티리밍 알람 받으면 true 안받으면 false
 * @property int $comment_push 댓글 알람 받으면 true 안받으면 false
 * @property int $board_push 게시물 승인 알람 받으면 true 안받으면 false
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereBoardPush($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCommentPush($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereStreamingPush($value)
 */
class Device extends Model
{
    protected $fillable = [
        'app',
        'user_id',
        'device',
        'os_version',
        'store_type',
        'app_version',
        'fcm_token',
        'device_key',
        'is_push',
        'streaming_push',
        'comment_push',
        'board_push'
    ];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
