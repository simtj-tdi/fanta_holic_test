<?php

namespace App\Http\Controllers\Api\Auth;


use App\Device;
use App\Lib\UserManagement;
use App\Lib\Util;
use App\Standard;
use App\User;
use App\UserLoginHistory;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Lib\Response;
use Illuminate\Validation\ValidationException;
use App\Lib\Log;
use Ixudra\Curl\Facades\Curl;

class ApiAuthController extends Controller
{
    /**
     *
     * @param  [string] nickname  //닉네임
     * @param  [string] name      //이름
     * @param  [string] email     //이메일(id)
     * @param  [string] password  //비밀번호
     * @param  [string] password_confirmation  //비밀번호 확인
     * @return [string] message
     */

//    protected $util;
    protected $config;
    protected $client;
    protected $response;

    public function __construct()
    {
        $this->config = app('config')['celeb'];

        $this->response = new Response();
    }


    //로그인 => 토큰 발급 v3
    public function login_v3(Request $request)
    {
        try {
            $request->validate([
                'app' => 'string',
                'email' => 'required|string|email',
                'password' => 'required',
                'ad_id' => 'string',
                'store_type' => 'string',
                'device' => 'string',
                'os_version' => 'string',
                'app_version' => 'string',
                'fcm_token' => 'string'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
//                    'message' => $e->errors()['email'][0],
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $app = $request->input('app', 'fantaholic');
        // 이메일 확인
        if (User::where('email', $request->email)->where('app', $app)->count() == 0) {
            return $this->response->set_response(-3003, null);
        }

        //비밀번호 확인
        if (!Hash::check($request->input('password'),
            User::where('email', $request->input('email'))->where('app', $app)->get()->first()->password)) {
            return $this->response->set_response(-3010, null);
        }

        $user = User::where('email', $request->email)->where('app', $app)->get()->first();

        Auth::login($user);

        //기존 로그인 토큰들 다 폐기 => 중복 로그인 방지
        $tokens = $user->tokens;

        foreach ($tokens as $token) {
            $token->revoke();
        }

        //등록된 디바이스인지 검색
        $device_check = $user->devices()->where('device_key', $request->ad_id)->where('app', $app)->get();

        //같은 디바이스 있으면 update + 값이 null로 들어오 경우가 있음 => null이면 update x
        if ($device_check->count() != 0) {
            $update = [
                'user_id' => $user->id,
                'app' => $app
            ];
            if ($request->input('device') != null) {
                $update['device'] = $request->input('device');
            }
            if ($request->input('store_type') != null) {
                $update['store_type'] = $request->input('store_type');
            }
            if ($request->input('ad_id') != null) {
                $update['device_key'] = $request->input('ad_id');
            }
            if ($request->input('os_version') != null) {
                $update['os_version'] = $request->input('os_version');
            }
            if ($request->input('app_version') != null) {
                $update['app_version'] = $request->input('app_version');
            }
            if ($request->input('fcm_token') != null) {
                $update['fcm_token'] = $request->input('fcm_token');
            }

            Device::where('device_key', $request->ad_id)
                ->where('app', $app)
                ->update($update);

            //없는 디바이스면 생성
        } else {
            $user->devices()->create([
                'app' => $app,
                "device" => $request->device,
                'store_type' => $request->store_type,
                'device_key' => $request->ad_id,
                'os_version' => $request->os_version,
                'app_version' => $request->app_version,
                'fcm_token' => $request->fcm_token,
                'is_push' => 1
            ])->get();
        }

        $device = $user->devices()->where('device_key', $request->ad_id)->get()->last();

        $tokenResult = $user->createToken('Personal Access Token');

        //이부분 바꾸면 토큰 유효기간 바뀔 거처럼 보이는대 안봐낌 => mysql DB 기록 용도로만 생각
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->adddays(1);
        $token->save();

        // 핀시 아이템 없음 보상없음 =>제외
        if ($app != 'pinxy') {
            //당일 로그인 기록 확인
            $user_manage = new UserManagement();
            $day_attendance_check = $user_manage->day_login_check($app, $user);
            //첫로그인이면 아이템 보상
            if (!$day_attendance_check) {
                //로그인 보상 개수
                $login_reward = Standard::where('app', $app)->get()->last()->login_reward;
                //아이템 지급
                $user_item_count = $user_manage->additem($app, $user->id, $login_reward);
            } else {
                $user_item_count = $user->item_count;
            }
        }

        //로그인 기록
        $util = new Util();

        UserLoginHistory::create([
            'account' => $user->email,
            'ad_id' => $request->ad_id,
            'app' => $app,
            'device' => $request->input('device'),
            'ip' => $util->getIpFromProxy($request),
            'os_version' => $request->input('os_version'),
            'store_type' => $request->input('store_type'),
            'user_id' => $user->id,
        ]);

        //week_grade
        $data = [
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'sns_type' => $user->sns_type->value,
            'birth' => isset($user->birth) ? $user->birth : '',
            'device' => $device->device,
            'store_type' => $device->store_type,
            'os_version' => $device->os_version,
            "app_version" => $device->app_version,
            'is_push' => $device->is_push,
            'profile_photo_url' => $user->profile_photo_url,
            'gender' => $user->gender,
            'black' => $user->black,
            'isDayCheck' => $day_attendance_check,
            'total_item' => (int)($user_item_count),
            "mobile" => $user->mobile
        ];

        return $this->response->set_response(0, $data);
    }


    public function another_log()
    {
//        Auth::loginUsingId();
        $user = User::whereId(214)->get()->first();
        $test = Auth::loginUsingId(214, true);

        //기존 로그인 토큰들 다 폐기 => 중복 로그인 방지


//        $token = $tokens->first()->;

        $tokenResult = $user->createToken('Personal Access Token');

        //이부분 바꾸면 토큰 유효기간 바뀔 거처럼 보이는대 안봐낌 => mysql DB 기록 용도로만 생각
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->adddays(1);
        $token->save();
        dd($tokenResult->accessToken);

    }

    public function social_login_v3(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
                'sns_type' => 'required|string',
                'sns_id' => 'nullable|string',
                'code' => 'nullable|string',
                'app_version' => 'required|string',
                'store_type' => 'required|string',
                'device' => 'nullable|string',
                'os_version' => 'nullable|string',
                'ad_id' => 'required|string',
                'fcm_token' => 'required'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $params = [
            'app' => $request->input('app'),
            'sns_type' => $request->input('sns_type'),
            'app_version' => $request->input('app_version'),
            'store_type' => $request->input('store_type'),
            'device' => $request->input('device'),
            'os_version' => $request->input('os_version'),
            'ad_id' => $request->input('ad_id'),
            'fcm_token' => $request->input('fcm_token')
        ];

        // get sns_id start
        $sns_id = $request->input('sns_id');
        $wechat_code = $request->input('code');

        if ($sns_id != null) {
            $params['sns_id'] = $sns_id;
        } else { //위챗 로그인
            if ($wechat_code == null) {
                return $this->response->set_response(-1001, null);
            }
            $options = array(
                'appid' => config('celeb')[$params['app']]['wechat_app_id'],
                'secret' => config('celeb')[$params['app']]['wechat_secret'],
                'code' => $wechat_code,
                'grant_type' => 'authorization_code'
            );

            $wechat_api = 'https://api.weixin.qq.com/sns/oauth2/access_token?';

            $detail_api = $wechat_api . http_build_query($options, 'a', '&');
            $detail_res = Curl::to($detail_api)->get();
            $detail_res = json_decode($detail_res, true);

            //api return 값에 유저 id 없으면 실패로 간주
            if (!isset($detail_res['unionid'])) {
                return $this->response->set_response(-3012, null);
            } else {
                $params['sns_id'] = $detail_res['unionid'];
            }
        }
        //get sns_id end

        //sns id 값으로 탈퇴한 유저인지 확인
        $withdraw_sns_user = User::onlyTrashed()
            ->where('sns_type', $params['sns_type'])
            ->where('sns_id', $params['sns_id'])
            ->where('app', $params['app'])
            ->where('deleted_at', '>', Carbon::now()->addWeeks(-1))
            ->get()
            ->count();

        if ($withdraw_sns_user > 0) {
            return $this->response->set_response(-3011, null);
        }

        // sns id 값으로 존재하는 유저인지 확인
        $user_search_query = User::where('sns_type', $params['sns_type'])
            ->where('sns_id', $params['sns_id'])
            ->where('app', $params['app']);

        // 없음 => 생성
        if ($user_search_query->count() == 0) {
            $user = new User([
                'app' => $params['app'],
                'sns_type' => $params['sns_type'],
                'sns_id' => $params['sns_id'],
                'gender' => 2
            ]);
            $user->save();
            //핀시용 유저 좋아요태그
            $user->usertags()->create([
                'app' => $params['app']
            ]);
            $user->icert()->create([
                'app' => $params['app']
            ]);
//        이미 있는 유저
        } else {
            $user = $user_search_query->get()->last();
            Auth::login($user);

            //중복로그인 차단 => 기존 로그인 토큰 폐기
            $tokens = $user->tokens;
            foreach ($tokens as $token) {
                $token->revoke();
            }
        }
        //유저 생성 및검색끝

        //등록된 디바이스인지 검색
        $device_search_query = Device::where('app', $params['app'])->where('device_key', $params['ad_id']);

        //있으면 user_id 업데이트
        // + 값이 null로 들어오 경우가 있음 => null이면 update x
        if ($device_search_query->count() > 0) {
            $device_update = [
                'user_id' => $user->id,
                'app' => $params['app']
            ];
            if ($params['ad_id'] != null) {
                $device_update['device_key'] = $params['ad_id'];
            }
            if ($params['store_type'] != null) {
                $device_update['store_type'] = $params['store_type'];
            }
            if ($params['device'] != null) {
                $device_update['device'] = $params['device'];
            }
            if ($params['os_version'] != null) {
                $device_update['os_version'] = $params['os_version'];
            }
            if ($params['app_version'] != null) {
                $device_update['app_version'] = $params['app_version'];
            }
            if ($params['fcm_token'] != null) {
                $device_update['fcm_token'] = $params['fcm_token'];
            }

            $device_search_query->update($device_update);
        } else {
            //없는 디바이스면 생성
            $user->devices()->create([
                'app' => $params['app'],
                'device_key' => $params['ad_id'],
                'store_type' => $params['store_type'],
                'device' => $params['device'],
                'os_version' => $params['os_version'],
                'app_version' => $params['app_version'],
                'fcm_token' => $params['fcm_token'],
                'is_push' => 1
            ]);
        }
        //디바이스 검색 및 생성 끝

        //  로그인 처리
        $tokenResult = $user->createToken('Personal Access Token');

        //이부분 바꾸면 토큰 유효기간 바뀔 거처럼 보이는대 안봐낌 => mysql DB 기록 용도로만 생각
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->adddays(1);
        $token->save();

        // 핀시 아이템 없음 => 로그인 보상 제외
        if ($params['app'] != 'pinxy') {
            $user_manage = new UserManagement();
            $day_attendance_check = $user_manage->day_login_check($params['app'], $user);

            if (!$day_attendance_check) {
                //로그인 보상 개수
                $login_reward = Standard::where('app', $params['app'])->get()->last()->login_reward;

                //아이템 지급
                $user_item_count = $user_manage->additem($params['app'], $user->id, $login_reward);

            } else {
                $user_item_count = $user->item_count;
            }
        } else {
            $user_item_count = $user->item_count;
        }

        // ES 로그인 기록
        //로그인 기록
        $util = new Util();

        // Save ES Log (Redis Cache)
        UserLoginHistory::create([
            'account' => $user->email,
            'ad_id' => $params['ad_id'],
            'app' => $params['app'],
            'device' => $params['device'],
            'ip' => $util->getIpFromProxy($request),
            'os_version' => $params['os_version'],
            'store_type' => $params['store_type'],
            'user_id' => $user->id,
        ]);

        $device = $user->devices()->where('device_key', $params['ad_id'])->get()->last();

        $data = [
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user_id' => $user->id,
//            'sns_type'      =>  ($user->sns_type != null) ? $user->sns_type : "",
            'sns_id' => ($user->sns_id != null) ? $user->sns_id : "",
            'sns_type' => $user->sns_type->value ,
            'name' => ($user->name != null) ? $user->name : "",
            'email' => ($user->email != null) ? $user->email : "",
            'nickname' => ($user->nickname != null) ? $user->nickname : '',
            'birth' => ($user->birth != null) ? $user->birth : '',
            'device' => $params['device'],
            'store_type' => $params['store_type'],
            'os_version' => $params['os_version'],
            "app_version" => $params['app_version'],
            'gender' => $user->gender,
            'black' => $user->black,
            'is_push' => $device->is_push,
            'profile_photo_url' => $user->profile_photo_url,
            'total_item' => $user_item_count,
            'mobile' => ($user->mobile != null) ? $user->mobile : ""
        ];
        return $this->response->set_response(0, $data);
    }

//    로그아웃 =>토큰 폐기
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->response->set_response(0, null);
    }
}
