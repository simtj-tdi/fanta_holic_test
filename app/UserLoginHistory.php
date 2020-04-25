<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserLoginHistory
 *
 * @property int $id
 * @property string|null $account account
 * @property string $ad_id Device ad_id
 * @property string $app 로그인 App
 * @property string $device 로그인 Device
 * @property string $ip 로그인 IP
 * @property string $os_version OS Version
 * @property string $store_type OS
 * @property int $user_id user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereAdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereStoreType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLoginHistory whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\User $user
 */
class UserLoginHistory extends Model
{

    protected $fillable = ['account', 'ad_id', 'app', 'device', 'ip', 'os_version', 'store_type', 'user_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
