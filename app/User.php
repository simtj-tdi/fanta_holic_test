<?php

namespace App;

use App\Enums\UserSnsType;
use BenSampo\Enum\Traits\CastsEnums;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * App\User
 *
 * @property int $id id
 * @property string|null $email 이메일
 * @property \Illuminate\Support\Carbon|null $email_verified_at 이메일 인증
 * @property string|null $password 비밀번호
 * @property string|null $identity_verfied_at 본인인증 유무
 * @property string|null $mobile_verified_at 휴대폰인증 유무
 * @property string|null $birth 생년월일
 * @property int|null $gender 성별
 * @property string|null $mobile 휴대폰 번호
 * @property string|null $name 이름
 * @property string|null $nickname 닉네임
 * @property string|null $last_logged_at 마지막 로그인
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $repost 게시물 다시보기 여부 0 : 안봄 1 : 봄
 * @property string|null $app 앱 구분자
 * @property int|null $item_count 유저 아이템 개수
 * @property string|null $sns_type 소셜 로그인 타입
 * @property string|null $sns_id 소셜로그인 id
 * @property int $is_admin 관리자 여부
 * @property int $black 블랙유저 true 아니면 false
 * @property string|null $profile_photo_url 프로필 사진 url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Device[] $devices
 * @property-read int|null $devices_count
 * @property-read \App\Icert $icert
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserItem[] $useritems
 * @property-read int|null $useritems_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserResponseToBoard[] $userresponsetoboard
 * @property-read int|null $userresponsetoboard_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserResponseToComment[] $userresponsetocomment
 * @property-read int|null $userresponsetocomment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserScore[] $userscores
 * @property-read int|null $userscores_count
 * @property-read \App\UserTag $usertags
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBlack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIdentityVerfiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereItemCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastLoggedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMobileVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereProfilePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRepost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSnsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSnsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 * @property string $timezone 시간대
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezone($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Board[] $board
 * @property-read int|null $board_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comment
 * @property-read int|null $comment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserItem[] $userItem
 * @property-read int|null $user_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserLoginHistory[] $userLoginHistory
 * @property-read int|null $user_login_history_count
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes,  CastsEnums;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'app',
        'black',
        'email',
        'email_verified_at',
        'password',
        'identity_verified_at',
        'mobile_verified_at',
        'birth',
        'gender',
        'mobile',
        'name',
        'nickname',
        'last_logged_at',
        'deleted_at',
        'remember_token',
        'created_at',
        'updated_at',
        'repost',
        'sns_type',
        'sns_id',
        'profile_photo_url',
        'timezone'
    ];

    protected $enumCasts = [
        'sns_type' => UserSnsType::class
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates =[ 'created_at' , 'updated_at' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sns_type' => 'string'
    ];

    public function icert()
    {
        return $this->hasOne(Icert::class);
    }

    public function usertags()
    {
        return $this->hasOne(UserTag::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function userresponsetoboard()
    {
        return $this->hasMany(UserResponseToBoard::class);
    }

    public function userresponsetocomment()
    {
        return $this->hasMany(UserResponseToComment::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class)->orderBy('id','desc');
    }

    public function userItem()
    {
        return $this->hasMany(UserItem::class);
    }

    public function board()
    {
        return $this->hasMany(Board::class)->orderBy('id','desc');
    }

    public function userLoginHistory()
    {
        return $this->hasMany(UserLoginHistory::class)->orderBy('id','desc');
    }


    static function getList(array $params)
    {
        if (empty($params['app'])) {
            Log::error(__METHOD__ . ' - validation fail - ' . json_encode($params));
            throw new Exception('validation fail ');
        }
        $query = self::where('app', $params['app']);
        $query = $query->when(!empty($params['schVal']), function ($query) use ($params) {
            $schColumn = ['email', 'name', 'nickname', 'mobile'];

            if (!empty($params['schType'])) {
                $query = $query->where($params['schType'], $params['schType']);
            } else {
                foreach ($schColumn as $schKey => $column) {
                    if ($schKey == 0) {
                        $query = $query->where($column, 'like', '%' . $params['schVal'] . '%');
                    } else {
                        $query = $query->orWhere($column, 'like', '%' . $params['schVal'] . '%');
                    }
                }
            }
            return $query;
        });

        $query = $query->when($params['userType'], function ($query) use ($params) {
            if ($params['userType'] == 'user') {
                return $query->whereNotNull('email');
            } else {
                return $query->wherenull('email');
            }
        });

        $query = $query->whereBetween($params['schDateType'],
            [
                Carbon::parse($params['startDate'])->startOfDay()->format('Y-m-d H:i:s'),
                Carbon::parse($params['endDate'])->endOfDay()->format('Y-m-d H:i:s')
            ]);
        $query = $query->where('is_admin',false);
        $query = $query->orderBy('created_at', 'desc');
        return $query;
    }
}
