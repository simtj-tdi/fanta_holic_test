<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Icert
 *
 * @property int $id id
 * @property int $user_id 유저 id
 * @property string|null $icert_name 이름 / 본인확인
 * @property string|null $icert_birthday 생년월일 / 본인확인
 * @property string|null $icert_mobile 전화번호 / 본인확인
 * @property string|null $icert_gender 성별 / 본인확인
 * @property string|null $icert_nation 외국인 여부 / 본인확인 => 내국인:0 , 외국인:1
 * @property string|null $app 앱 구분자
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereIcertBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereIcertGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereIcertMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereIcertName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereIcertNation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Icert whereUserId($value)
 * @mixin \Eloquent
 */
class Icert extends Model
{
    protected $fillable = [
        'app',
        'user_id',
        'icert_name',
        'icert_birthday',
        'icert_mobile',
        'icert_gender',
        'icert_nation'
    ];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
