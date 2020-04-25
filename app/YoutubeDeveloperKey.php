<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\YoutubeDeveloperKey
 *
 * @property int $id
 * @property string|null $key 키
 * @property int $state 키  사용가능여부 default =0  [ 0 : 사용불가 ,1 :사용가능]
 * @property int $count 키 사용량 [유투브 제한량 초기화시간(오후 3시나 4시)에 0으로 초기화]
 * @property string|null $account 해당키 계정
 * @property string|null $password 해당키 비밀번호
 * @property string|null $comment 현재 해당키 사용용도 설명 [ ex "크롤링" , "클라이언트 유투브 불러오기용" 등등]
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\YoutubeDeveloperKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class YoutubeDeveloperKey extends Model
{
    protected $fillable = [
        'key',
        'state',
        'count',
        'account',
        'password',
        'comment'
    ];
}
