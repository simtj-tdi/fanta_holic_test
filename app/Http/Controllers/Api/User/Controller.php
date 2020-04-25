<?php

namespace App\Http\Controllers\Api\User;

use App\Comment;
use App\Enums\UserItemType;
use App\Lib\LobbyClassv6;
use App\Lib\UserManagement;
use App\Lib\Util;
use App\Music;
use App\UserItem;
use App\Board;
use App\UserLoginHistory;
use App\UserResponseToBoard;
use App\Device;
use App\User;
use App\UserScoreLog;
use Carbon\Carbon;
use App\Lib\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as baseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Lib\Log;

class Controller extends baseController
{
    protected $config;
    protected $client;
    protected $response;

    public function __construct()
    {
        $this->config = app('config')['celeb'];
        $this->response = new Response();
    }

    public function user_boards(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string'
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

        $user = $request->user();
        $app = $request->input('app', 'fantaholic');
        $next = $request->input('next', 0);
        $page_count = 20;

        //마이핀( 좋아요 한 게시물 )


        $boards = Board::join('user_response_to_boards', 'user_response_to_boards.board_id', '=', 'boards.id')
            ->where('user_response_to_boards.response', '=', 1)
            ->where('user_response_to_boards.user_id', '=', $user->id)
            ->where('boards.app', $app)
            ->where('user_response_to_boards.app', $app)
            ->select('boards.*')
            ->orderBy('created_at', 'desc')
            ->Paginate($page_count, ['*'], 'next_page');

        if ($boards->count() == 0) {
            return $this->response->set_response(-2001, null);
        }

        // set next_page
        if (!$boards->hasMorePages()) {
            $result['next_page'] = '-1';
        } else {
            if ($next) {
                $result['next_page'] = string($next + 1);
            } else {
                $result['next_page'] = '2';
            }
        }
        $lobbyClass = new LobbyClassv6();
        $boards = $lobbyClass->board_parsing($boards, $user);
        $result['cdn_url'] = app('config')['celeb'][$app]['cdn'];
        $result['shared_url'] = app('config')['celeb'][$app]['shared_url'];
        $result['boards'] = $boards;

        return $this->response->set_response(0, $result);
    }

    public function user_ban_boards(Request $request)
    {
        $app = $request->input('app', 'fantaholic');
        $user_id = $request->user()->id;;
        //마이핀( 좋아요 한 게시물
        $user_response_to_boards = UserResponseToBoard::where('user_id', $user_id)
            ->where('response', 0)
            ->where('app', $app)
            ->get();

        if (count($user_response_to_boards) != 0) {
            foreach ($user_response_to_boards as $user_response_to_board) {
                $board_ids[] = $user_response_to_board->board_id;
            }
            $boards = Board::whereIn('id', $board_ids)->orderBy('created_at', 'desc')->get();
        } else {
            $boards = [];
        }
        $data = [
            'cdn_url' => $this->config = app('config')['celeb'][$app]['cdn'],
            'boards' => $boards
        ];
        return $this->response->set_response(0, $data);
    }

    public function user_v2(Request $request)
    {
        $user = $request->user();

        $data = [
            'user_id' => $user->id,
            "profile_photo_url" => ($user->profile_photo_url != null) ? $user->profile_photo_url : '',
            'sns_type' => ($user->sns_type != null) ? $user->sns_type : '',
            'sns_id' => ($user->sns_id != null) ? $user->sns_id : '',
            'email' => $user->email,
            'birth' => $user->birth,
            'gender' => $user->gender,
            'mobile' => $user->mobile,
            'name' => $user->name,
            'nickname' => $user->nickname,
            'is_admin' => $user->is_admin,
            'black' => $user->black,
        ];

        return $this->response->set_response(0, $data);
    }

    //앱구동시 유저 찾거나 생성
    public function store(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'store_type' => 'required|string',
                'device' => 'required|string',
                'os_version' => 'required|string',
                'app_version' => 'nullable|string',
                'fcm_token' => 'nullable|string',
                'guest_id' => 'nullable',
                'app_version' => 'required|string',
                'ad_id' => 'required',
                'app' => 'string',
                'timezone' => 'string'
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

        $app = $request->input('app','fantaholic');

        $params = [
            'app' => $app,
            'device_key' => $request->input('ad_id'),
            'store_type' => $request->input('store_type'),
            'device' => $request->input('device'),
            'os_version' => $request->input('os_version'),
            'app_version' => $request->input('app_version'),
            'fcm_token' => $request->input('fcm_token')
        ];

        //guest_id 검색 => 예전 값 불러오는 문제가 계속 발생 => guest_id 검색 안하던지 ad_id기준 검색 부터 적용해야할듯함
        if ($params['device_key'] != null) {
            $device = Device::where('device_key', $request->input('ad_id'))->where('app', $app)->get()->last();
            if ($device != null) {
                $user = User::where('id', $device->user_id)
                    ->get()->last();
            }
        }

        if (!isset($user) || $user == null) {
            $new_user = [
                'app' => $app
            ];
            if ($request->timezone != null) {
                $new_user['timezone'] = $request->timezone;
            }
            $user = new User($new_user);
            $user->save();
            $user->devices()->create($params);
            $user->create([
                'app' => $app
            ]);
            $user->icert()->create([
                'app' => $app
            ]);
            $user_id = $user->id;
        } else {
            if ($request->timezone != null) {
                $user->timezone = $request->timezone;
                $user->save();
            }

            $user_id = $user->id;
            $params['user_id'] = $user_id;
            $user->devices()->where('app', $app)->where('device_key', $params['device_key'])->update($params);
        }


        //앱 강제 업데이트 여부
        if (app('config')['update_check'][$app]['version'][$params['store_type']]['app_ver'] == $params['app_version']) {
            $forced_udpate = 0;
        } else {
            if (app('config')['update_check'][$app]['version'][$params['store_type']]['forced_update'] == 'auto') {
                $forced_udpate = 1;
            } elseif (app('config')['update_check'][$app]['version'][$params['store_type']]['forced_update'] == 'forced') {
                $forced_udpate = 2;
            }
        }

        return response()->json([
            'data' => [
                'cdn_url' => app('config')['celeb'][$app]['cdn'],
                'user_id' => (string)$user_id,
//                'icert'     => $icert,
//                'adult'     => $adult,
//                'gender'    => $user->gender,
//                'tag'       =>  $user_tags,
                'forced_update' => $forced_udpate
            ],
            'resultCode' => [
                'code' => 0,
                'message' => 'Success'
            ]
        ]);
    }

    public function email_check(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'app' => 'string'
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
        $app = $request->input('app', 'fantaholic');

        $duple_check = User::where('email', $request->input('email'))->where('app', $app)->count();
        if ($duple_check > 0) {
            return $this->response->set_response(-3001, null);
        }
        return response()->json([
            'resultCode' => [
                'code' => 0,
                'message' => 'success'
            ]
        ]);
    }

    public function nickname_check(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'nickname' => 'required|string',
                'app' => 'string'
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

        $app = $request->input('app', 'fantaholic');

        $duple_check = User::where('nickname', $request->input('nickname'))
            ->where('app', $app)->count();

        if ($duple_check > 0) {
            return $this->response->set_response(-3001, null);
        }
        return response()->json([
            'resultCode' => [
                'code' => 0,
                'message' => 'success'
            ]
        ]);
    }


    //영어 2~12글자 한글 2~8 ,특수문자 불가,한영 혼합 불가
    function nickname_validate($str)
    {
        // 3 특수문자
        $pattern = '/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9a-zA-Z]+/u';
        if (preg_match($pattern, $str)) {
            return 3;
        }
        // 1 한글
        $pattern = '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]+/u';
        if (preg_match($pattern, $str)) {
            // 3 한글 영문 혼용
            $pattern = '/[a-zA-Z]/';
            if (preg_match($pattern, $str)) {
                return 3;
            }

            return 1;
        }

        return 2;
    }

    public function nickname_check_v2(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'nickname' => ($this->nickname_validate($request->nickname) == 3) ? 'required|string|max:0' : (
                ($this->nickname_validate($request->nickname) == 2) ? 'required|string|min:2|max:12' : 'required|string|min:2|max:8'),
                'app' => 'string'
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());
            if (in_array('nickname', array_keys($e->validator->failed()))) {
                return $this->response->set_response(-3013, null);
            }
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }

        $app = $request->input('app', 'fantaholic');

        $duple_check = User::where('nickname', $request->input('nickname'))
            ->where('app', $app)->count();

        if ($duple_check > 0) {
            return $this->response->set_response(-3001, null);
        }
        return response()->json([
            'resultCode' => [
                'code' => 0,
                'message' => 'success'
            ]
        ]);
    }

    public function push_state(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string',
                'ad_id' => 'required|string',
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

        $app = $request->app;
        $user = $request->user();
        $device = $user->devices()->where('device_key', $request->input('ad_id'))->get()->last();

        Log::debug(__FILE__, __LINE__, print_r($device, true));

        $data = [
            'user_id' => $user->id,
            'is_push' => $device->is_push,
            'streaming_push' => $device->streaming_push,
            'comment_push' => $device->comment_push,
            'board_push' => $device->board_push,
        ];

        return $this->response->set_response(0, $data);

    }

    // push 전체 업데이트
    public function push_update(Request $request, $user_id)
    {
        try {
            $request->validate([
                'ad_id' => 'required|string',
//                'is_push'   =>  'required',
//                'streaming_push'   =>  'required',
//                'comment_push'   =>  'required',
//                'board_push'   =>  'required',
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
            'is_push' => $request->input('is_push', 1),
            'streaming_push' => $request->input('streaming_push', 1),
            'comment_push' => $request->input('comment_push', 1),
            'board_push' => $request->input('board_push', 1),
        ];
        $user = $request->user();
        $device = $user->devices()->where('device_key', $request->input('ad_id'))->get()->last();
        $device->update($params);
        $data = [
            'user_id' => $user->id,
            'is_push' => $device->is_push,
            'streaming_push' => $device->streaming_push,
            'comment_push' => $device->comment_push,
            'board_push' => $device->board_push,
        ];

        return $this->response->set_response(0, $data);
    }

    public function password_update(Request $request, $user_id)
    {

        if (Hash::check($request->input('password'), $request->user()->password)) {
            return $this->response->set_response(-3010, null);
        }

        $request->user()->update(['password' => Hash::make($request->input('password'))]);

        $data = [
            'user_id' => (string)$request->user()->id,
        ];
        return $this->response->set_response(0, $data);
    }

    public function nickname_update(Request $request, $user_id)
    {
        try {
            $request->validate([
                'nickname' => 'required|string'
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
        $user = $request->user();
        $user->update(['nickname' => $request->input('nickname')]);

        $data = [
            'user_id' => (string)$user->id,
            'nickname' => $user->nickname,
        ];
        return $this->response->set_response(0, $data);
    }

    //소셜 로그인 이후 닉네임 생년월일 없을경우 update
    public function additional_info(Request $request, $user_id)
    {
        try {
            $request->validate([
                'gender' => 'required|integer',
                'nickname' => 'required|string',
                'birth' => 'required|string',
                'app' => 'string'
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

        $user = $request->user();

        $user->update([
            'gender' => $request->input('gender'),
            'nickname' => $request->input('nickname'),
            'birth' => $request->input('birth')
        ]);

        return $this->response->set_response(0, null);

    }

    public function gender_update(Request $request, $user_id)
    {
        $login_user_check = Auth('api')->user();

        if ($login_user_check != null) {
            $user = $login_user_check;
            $user->update(['gender' => $request->input('gender')]);
        } else {
            User::where('id', $user_id)
                ->update([
                    'gender' => $request->input('gender')
                ]);
            $user = User::where('id', $user_id)
                ->get()
                ->first();
        }
        $data = [
            'user_id' => (string)($user->id),
            'gender' => $user->gender,
        ];
        return $this->response->set_response(0, $data);
    }

    //성인인증 끝나고 가입으로 가정 v2
    public function signup_v2(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'nickname' => 'required|string',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed',
                'ad_id' => 'required|string',
                'birth' => 'required|string',
                'store_type' => 'string',
                'device' => 'string',
                'os_version' => 'string',
                'app_version' => 'string',
                'guest_id' => 'string',
                'gender' => 'integer',
                'mobile' => 'required',
                'guest_id' => 'string',
                'app' => 'string',
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

        $this->redis = app('redis');
        $app = $request->input('app', 'fantaholic');
        //ad_id값으로 유저 검색

        //탈퇴한 유저인지 확인
        $withdraw_user = User::onlyTrashed()
            ->where('mobile', $request->input('mobile'))
            ->where('app', $app)
            ->where('email', '!=', null)
            ->where('password', '!=', null)
            ->where('deleted_at', '>', Carbon::now()->adddays(-7))
            ->get();

        if ($withdraw_user->count() > 0) {
            return $this->response->set_response(-3011, null);
        }

        $device_query = Device::where('device_key', $request->input('ad_id'))->where('app', $app);
        //기기 등록확인
        if ($device_query->count() > 1) {
            $device = null;
        } else {
            if ($device_query->count() != 0) {
                $device = $device_query->get()->last();
            } else {
                $device = null;
            }
        }

        if ($device != null &&
            $user = User::where('id', $device->user_id)
                ->where('app', $app)
                ->where('sns_type', null)
                ->where('sns_id', null)
                ->get()->last()
        ) {

        } else { // 앱로그인 통신 오류로 유저가 생성 안된경우 유저 생성
            $user = new User(['app' => $app]);
            $user->save();
            $user->devices()->create([
                'app' => $app,
                'device_key' => $request->input('ad_id'),
                'store_type' => $request->input('store_type'),
                'device' => $request->input('device'),
                'os_version' => $request->input('os_version'),
                'app_version' => $request->input('app_version'),
                'fcm_token' => $request->input('fcm_token'),
                'is_push' => 1
            ]);
            $user->icert()->create([
                'app' => $app
            ]);

            if ($device_query->count() > 1) {
                //todo 하나뺴고 나머지 다 삭제
                Device::where('device_key', $request->input('ad_id'))->update([
                    'user_id' => $user->id
                ]);
            }
        }

        //ad_id 기준 이미 가입한 계정이 있을경우 + 해당전화번호로 가입한 계정이 있을경우 => -3005
        if ((User::where('mobile', $request->input('mobile'))->where('app', $app)->get()->last() != null) ||
            (User::where('id',
                    Device::where('device_key', $request->input('ad_id'))
                        ->where('app', $app)
                        ->get()
                        ->last()
                        ->user_id)
                    ->where('app', $app)
                    ->where('email', '!=', null)
                    ->get()
                    ->last() != null)) {
            return $this->response->set_response(-3005, null);
        }

        //비회원 => 회원  update
        User::find($user->id)->update([
            'app' => $app,
            'name' => $request->name,
            'nickname' => $request->nickname,
            'birth' => $request->birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'mobile' => $request->mobile
        ]);

        $request_tags = $request->input('tags');
        //# 제거후 저장
        if ($request_tags != null) {
            $tags = array_map(function ($tag) {
                return substr($tag, 1);
            }, $request_tags);
        } else {
            $tags = [];
        }


        $this->redis->rpush('user_likes', json_encode([
            'user_id' => $user->id,
            'tag' => $tags,
            'reg_date' => Carbon::now()->toDateTimeString(),
            'reg_time' => Carbon::now()->timestamp,
        ]));

        $device = Device::where('user_id', $user->id)->where('device_key', $request->ad_id)->where('app',
            $app)->get()->last();

        //같은 디바이스 있으면 update
        if ($device != null) {
            $device->update([
                'app' => $app,
                'store_type' => $request->store_type,
                'device_key' => $request->ad_id,
                'os_version' => $request->os_version,
                'app_version' => $request->app_version,
                'is_push' => 1
            ]);
            //다른 디바이스 면 생성
        } else {
            $user->devices()->create([
                'app' => $app,
                'store_type' => $request->store_type,
                'device_key' => $request->ad_id,
                'os_version' => $request->os_version,
                'app_version' => $request->app_version,
                'is_push' => 1
            ])->get();
        }

        //로그인 처리
        $tokenResult = $user->createToken('Personal Access Token');

        //이부분 바꾸면 토큰 유효기간 바뀔 거처럼 보이는대 안봐낌 => mysql DB 기록 용도로만 생각
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->adddays(1);
        $token->save();

        $user_info = User::find($user->id);


        // 로그인 기록
        $util = new Util();
        $user_management = new UserManagement();
        $user_item_count = $user_management->additem($app, $user->id, UserScoreLog::FAN_DAY_ATTENDANCE_SCORE);

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

        $data = [
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'name' => $request->name,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'birth' => $request->birth,
            'device' => $device->device,
            'store_type' => $device->store_type,
            'os_version' => $device->os_version,
            "app_version" => $device->app_version,
            'gender' => $request->gender,
            'black' => $user->black,
            "user_tags" => $request_tags,
            'is_push' => $device->is_push,
            'repost' => $user_info->repost,
            'total_item' => $user_item_count,
            "mobile" => $user->mobile
        ];
        return $this->response->set_response(0, $data);

    }

    //성인인증 끝나고 가입으로 가정 v3
    public function signup_v3(Request $request)
    {
        try {
            $request->validate([
                'app' => 'string',
                'guest_id' => 'string',
                'name' => 'required|string',
                'nickname' => 'required|string',
                'birth' => 'required|string',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed',
                'ad_id' => 'required|string',
                "gender" => 'required',
                'store_type' => 'string',
                'device' => 'string',
                'os_version' => 'string',
                'app_version' => 'string',
                'mobile' => 'required',
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

        $this->redis = app('redis');
        $app = $request->input('app', 'fantaholic');
        //ad_id값으로 유저 검색

        //탈퇴한 유저인지 확인
        $withdraw_user = User::onlyTrashed()
            ->where('mobile', $request->input('mobile'))
            ->where('app', $app)
            ->where('email', '!=', null)
            ->where('password', '!=', null)
            ->where('deleted_at', '>', Carbon::now()->adddays(-7))
            ->get();

        if ($withdraw_user->count() > 0) {
            return $this->response->set_response(-3011, null);
        }

        $device_query = Device::where('device_key', $request->input('ad_id'))->where('app', $app);
        //기기 등록확인
        if ($device_query->count() > 1) {
            $device = null;
        } else {
            if ($device_query->count() != 0) {
                $device = $device_query->get()->last();
            } else {
                $device = null;
            }
        }

        if ($device != null &&
            $user = User::where('id', $device->user_id)
                ->where('app', $app)
                ->where('sns_type', null)
                ->where('sns_id', null)
                ->get()->last()
        ) {

        } else { // 앱로그인 통신 오류로 유저가 생성 안된경우 유저 생성
            $user = new User(['app' => $app]);
            $user->save();
            $user->devices()->create([
                'app' => $app,
                'device_key' => $request->input('ad_id'),
                'store_type' => $request->input('store_type'),
                'device' => $request->input('device'),
                'os_version' => $request->input('os_version'),
                'app_version' => $request->input('app_version'),
                'fcm_token' => $request->input('fcm_token'),
                'is_push' => 1
            ]);
            $user->icert()->create([
                'app' => $app
            ]);

            if ($device_query->count() > 1) {
                //todo 하나뺴고 나머지 다 삭제
                Device::where('device_key', $request->input('ad_id'))->update([
                    'user_id' => $user->id
                ]);
            }
        }

        //ad_id 기준 이미 가입한 계정이 있을경우 + 해당전화번호로 가입한 계정이 있을경우 => -3005
        if ((User::where('mobile', $request->input('mobile'))->where('app', $app)->get()->last() != null) ||
            (User::where('id',
                    Device::where('device_key', $request->input('ad_id'))
                        ->where('app', $app)
                        ->get()
                        ->last()
                        ->user_id)
                    ->where('app', $app)
                    ->where('email', '!=', null)
                    ->get()
                    ->last() != null)) {
            return $this->response->set_response(-3005, null);
        }

        //비회원 => 회원  update
        User::find($user->id)->update([
            'app' => $app,
            'name' => $request->name,
            'nickname' => $request->nickname,
            'birth' => $request->birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'mobile' => $request->mobile,
        ]);




        $device = Device::where('user_id', $user->id)->where('device_key', $request->ad_id)->where('app',
            $app)->get()->last();

        //같은 디바이스 있으면 update
        if ($device != null) {
            $device->update([
                'app' => $app,
                'store_type' => $request->store_type,
                'device_key' => $request->ad_id,
                'os_version' => $request->os_version,
                'app_version' => $request->app_version,
                'is_push' => 1
            ]);
            //다른 디바이스 면 생성
        } else {
            $user->devices()->create([
                'app' => $app,
                'store_type' => $request->store_type,
                'device_key' => $request->ad_id,
                'os_version' => $request->os_version,
                'app_version' => $request->app_version,
                'is_push' => 1
            ])->get();
        }

        //로그인 처리
        $tokenResult = $user->createToken('Personal Access Token');

        //이부분 바꾸면 토큰 유효기간 바뀔 거처럼 보이는대 안봐낌 => mysql DB 기록 용도로만 생각
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->adddays(1);
        $token->save();

        $user_info = User::find($user->id);


        // 로그인 기록
        $util = new Util();
        $user_management = new UserManagement();

        $userItem = new UserItem();
        $userItem->user_id = $user->id;
        $userItem->app = $app;
        $userItem->item_count  = UserScoreLog::FAN_SIGN_UP_SCORE;
        $userItem->log_type = UserItemType::SIGN_UP_ADD;
        $userItem->description = UserItemType::getDescription(UserItemType::SIGN_UP_ADD);
        $userItem->save();

        $user_management->additem($app, $user->id, UserScoreLog::FAN_SIGN_UP_SCORE);

        $userItem = new UserItem();
        $userItem->user_id = $user->id;
        $userItem->app = $app;
        $userItem->item_count  = UserScoreLog::FAN_DAY_ATTENDANCE_SCORE;
        $userItem->log_type = UserItemType::DAILY_LOGIN_ADD;
        $userItem->description = UserItemType::getDescription(UserItemType::DAILY_LOGIN_ADD);
        $userItem->save();
        $user_item_count = $user_management->additem($app, $user->id, UserScoreLog::FAN_DAY_ATTENDANCE_SCORE);
//        $user_item_count = $user_management->additem($app, $user->id, 10);

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


        $data = [
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'sns_type' => "",
            'birth' => $request->birth,
            'device' => $device->device,
            'store_type' => $device->store_type,
            'os_version' => $device->os_version,
            "app_version" => $device->app_version,
            'gender' => $request->gender,
            'black' => $user->black,
            'is_push' => $device->is_push,
            'total_item' => $user_item_count,
            "mobile" => $user->mobile
        ];
        return $this->response->set_response(0, $data);
    }

    //해당전화번호로 인증번호 발송 이미 가입한 유저인경우 return -3005
    public function validate_mobile(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'app' => 'string',
        ]);

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'mobile' => $request->input('mobile')
        ];

//        //탈퇴한 유저인지 확인
//        $withdraw_user = User::onlyTrashed()
//            ->where('mobile',$params['mobile'])
//            ->where('app',$params['app'])
//            ->where('email','!=',NULL)
//            ->where('password','!=',NULL)
//            ->where('deleted_at','>',Carbon::now()->adddays(-7))
//            ->get();
//
//        if($withdraw_user->count() > 0 ){
//            return $this->response->set_response(-3011,null);
//        }

        //이미 가입한 유저인지 확인
        $user = User::where('mobile', $params['mobile'])
            ->where('app', $params['app'])
            ->where('email', '!=', null)
            ->where('password', '!=', null)
            ->get();

        if ($user->count() > 0) {
            return $this->response->set_response(-3005, null);
        }


        $util = new Util();
        $number = $util->sendSNS($params['mobile'], $params['app']);

        $this->redis = app('redis');
        $check = $this->redis->get("Validate:number:{$params['mobile']}");

        if ($check != null) {
            $this->redis->del("Validate:number:{$params['mobile']}");
        }

        $this->redis->set("Validate:number:{$params['mobile']}", $number);
        $this->redis->expire("Validate:number:{$params['mobile']}", 300);

        if ($number == -9001) {
            return $this->response->set_response($number, null);
        } else {
            return $this->response->set_response(0, []);
        }
    }

    //가입한 번호인지 확인후 인증번호 발송 => 아이디 찾기 비밀번호 찾기전
    public function find_validate_mobile(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'app' => 'string'
        ]);

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'mobile' => $request->input('mobile'),
        ];

        $users = User::where('mobile', $params['mobile'])
            ->where('app', $params['app'])
            ->where('email', '!=', null)
            ->where('password', '!=', null)
            ->get();

        if ($users->count() > 0) {
            $util = new Util();
            $number = $util->sendSNS($params['mobile'], $params['app']);

            $this->redis = app('redis');
            $check = $this->redis->get("Validate:number:{$params['mobile']}");

            if ($check != null) {
                $this->redis->del("Validate:number:{$params['mobile']}");
            }

            $this->redis->set("Validate:number:{$params['mobile']}", $number);
            $this->redis->expire("Validate:number:{$params['mobile']}", 300);

            if ($number == -9001) {
                return $this->response->set_response($number, null);
            } else {
                return $this->response->set_response(0, []);
            }
        } else {
            return $this->response->set_response(-3006, null);
        }
    }

//    인증번호 서버 검증
    public function validate_sms_number(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'number' => 'required'
        ]);
        $params = [
            'number' => $request->input('number'),
            'mobile' => $request->input('mobile')
        ];
        $this->redis = app('redis');

        $check = $this->redis->get("Validate:number:{$params['mobile']}");
        if ($check == null) {
            return $this->response->set_response(-3009, null);
        } else {
            if ($check == $params['number']) {
                $this->redis->del("Validate:number:{$params['mobile']}");
                return $this->response->set_response(0, null);
            } else {
                return $this->response->set_response(-3008, null);
            }
        }
    }


    public function reset_mobile(Request $request)
    {
        $request->validate([
            'app' => 'string',
            'mobile' => 'required',
            'number' => 'required'
        ]);

        $params = [
            'app' => $request->input('app', 'fantaholic'),
            'mobile' => $request->input('mobile'),
            'number' => $request->input('number'),
        ];
        $this->redis = app('redis');

        $check = $this->redis->get("Validate:number:{$params['mobile']}");
        if ($check == null) {
            return $this->response->set_response(-3009, null);
        } else {
            if ($check == $params['number']) {
                $this->redis->del("Validate:number:{$params['mobile']}");

                $id = Auth::user()->id;
                $user = User::find($id);
                $user->mobile = $params['mobile'];
                $user->save();
                return $this->response->set_response(0, null);
            } else {
                return $this->response->set_response(-3008, null);
            }
        }
    }


    public function account_find(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'app' => 'string'
        ]);

        $app = $request->input('app', 'fantaholic');

        $users = User::where('mobile', $request->input('mobile'))
            ->where('app', $app)
            ->where('email', '!=', null)
            ->where('password', '!=', null)
            ->get();

        foreach ($users as $user) {
            $accounts[] = $user->email;
        }

        if ($users->count() > 0) {
            return $this->response->set_response(0, ['accounts' => $accounts[0]]);
        } else {
            return $this->response->set_response(-2001, null);
        }
    }

    public function reset_password(Request $request)
    {
        $validator = $this->validate($request, [
            'app' => 'string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $app = $request->input('app', 'fantaholic');

        if (Hash::check($request->input('password'),
            User::where('email', $request->input('email'))->where('app', $app)->get()->first()->password)) {
            return $this->response->set_response(-3010, null);
        }

        User::where('email', $request->input('email'))->where('app', $app)->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return $this->response->set_response(0, null);
    }

    public function withdraw(Request $request)
    {
        $user_id = $request->user()->id;
        $devices = Device::where('user_id', $user_id)->get();
        foreach ($devices as $device) {
            $device->delete();
        }
        //토큰 폐기
        $request->user()->token()->revoke();
        $request->user()->delete();

        return $this->response->set_response(0, null);
    }

    public function point_info(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
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

        $app = $request->input('app', 'fantaholic'); //엡이름
        $user = $request->user(); //유저정보
        $now_year = Carbon::now()->year; //현재년도
        $now_month = Carbon::now()->month;  //현재월


        //현재월 랭킹 , 유저 id , 점수합
        $user_ranking = DB::select("select rank,user_id,total from
                (select @vRank := @vRank +1 as rank,user_id,total
                from (
                select user_id,sum(score) as total
                from user_score_logs
                JOIN users on users.id = user_score_logs.user_id
                where year(user_score_logs.created_at) = {$now_year}
                and month(user_score_logs.created_at) = {$now_month}
                and user_score_logs.app = '{$app}'
                and users.deleted_at is null
                group by user_score_logs.user_id
                order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                )as cnt where user_id = {$user->id};
        ");


        if (count($user_ranking) != 0) {
            $user_ranking = $user_ranking[0];
            $month_ranking = $user_ranking->rank;  //현재월 랭킹
            $month_point = $user_ranking->total;  // 현재월 포인트합
            if ($user_ranking->rank == 1) {
                $point_gap = 0;                   //다음랭킹까지 남은 포인트 [1위는 0 고정]
            } else {
                $next_ranking = DB::select("select rank,user_id,total from
                (select @vRank := @vRank +1 as rank,user_id,total
                from (
                select user_id,sum(score) as total
                from user_score_logs
                JOIN users on users.id = user_score_logs.user_id
                where year(user_score_logs.created_at) = {$now_year}
                and month(user_score_logs.created_at) = {$now_month}
                and user_score_logs.app = '{$app}'
                and users.deleted_at is null
                group by user_score_logs.user_id
                order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                )as cnt where total > {$user_ranking->total} order by total asc limit 1;
              ");
                if (count($next_ranking) != 0) {
                    $point_gap = $next_ranking[0]->total - $user_ranking->total;
                } else {
                    $point_gap = 0;
                }
            }
        } else {  // 랭킹권에 없을경우
            $month_ranking = 0;
            $month_point = 0;
            $point_gap = 0;
        }

        return $this->response->set_response(0, [
            'month_point' => (int)$month_point,
            'month_ranking' => (int)$month_ranking,
            'total_item' => (int)$user->item_count,
            'point_gap' => (int)$point_gap,
            'profile_photo_url' => $user->profile_photo_url
        ]);
    }

    // 마이페이지 Poing/Ranking
    public function point_ranking(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string',
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

        $app = $request->app;
        $user = $request->user();
        $now_year = Carbon::now()->year;
        $now_month = Carbon::now()->month;
        // 1 누적 포인트
        $total_point = UserScoreLog::where('app', $app)->where('user_id', $user->id)
            ->sum('score');

        // 2 현재 월 랭킹
        $user_ranking = DB::select("select rank,user_id,total from
                (select @vRank := @vRank +1 as rank,user_id,total
                from (
                select user_id,sum(score) as total
                from user_score_logs
                JOIN users on users.id = user_score_logs.user_id
                where year(user_score_logs.created_at) = {$now_year}
                and month(user_score_logs.created_at) = {$now_month}
                and user_score_logs.app = '{$app}'
                and users.deleted_at is null
                group by user_score_logs.user_id
                order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                )as cnt where user_id = {$user->id};
        ");

        // 3 다음 랭킹까지 필요한 점수
        if (count($user_ranking) != 0) {
            $month_ranking = $user_ranking[0]->rank;

            if ($month_ranking == 1) {
                $point_gap = 0;                   //다음랭킹까지 남은 포인트 [1위는 0 고정]
            } else {
                $next_ranking = DB::select("select rank,user_id,total from
                (select @vRank := @vRank +1 as rank,user_id,total
                from (
                select user_id,sum(score) as total
                from user_score_logs
                JOIN users on users.id = user_score_logs.user_id
                where year(user_score_logs.created_at) = {$now_year}
                and month(user_score_logs.created_at) = {$now_month}
                and user_score_logs.app = '{$app}'
                and users.deleted_at is null
                group by user_score_logs.user_id
                order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                )as cnt where total > {$user_ranking[0]->total} order by total asc limit 1;
              ");
                if (count($next_ranking) != 0) {
                    $point_gap = $next_ranking[0]->total - $user_ranking[0]->total;
                } else { // 1등이랑 동점일경우도 남은 포인트 0고정
                    $point_gap = 0;
                }
            }
        } else {
            $month_ranking = 0;
            $point_gap = 0;
        }

        // 4 현재 월  랭킹 그래프 그리기용 랭킹 배열
        $util = new Util();
        $start = Carbon::createFromFormat('Y-m', $now_year . '-' . $now_month)->firstOfMonth(); // 해당 년월의 1일
        $mondays = $util->getMondays($now_year, $now_month);  // 월요일 배열 생성

        if (Carbon::now()->format('j') == '1') {
            $ranking_histories = [];
        } else {
            // 1일 0위 초기값 setting
            $day1_ranking_0 = new \stdClass();
            $day1_ranking_0->date = '1';
            $day1_ranking_0->ranking = 0;
            $ranking_histories[] = $day1_ranking_0;
            foreach ($mondays as $monday) {
                $ranking_history = new \stdClass();
                if ($monday >= Carbon::now()->startOfDay()) {
                    $now = Carbon::now();
                    $ranking_history->date = $now->format('j');
                    $ranking_history->ranking = $month_ranking;
                    $ranking_histories[] = $ranking_history;
                    break;
                } else {
                    $ranking_history->date = $monday->format('j');
                    $ranking = DB::select("select rank from
                        (select @vRank := @vRank +1 as rank,user_id,total
                        from (
                        select user_id,sum(score) as total
                        from user_score_logs
                        JOIN users on users.id = user_score_logs.user_id
                        where user_score_logs.created_at > '{$start}'
                        and user_score_logs.created_at < '{$monday}'
                        and user_score_logs.app = '{$app}'
                        and users.deleted_at is null
                        group by user_score_logs.user_id
                        order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                        )as cnt where user_id = {$user->id};
                    ");
                    $ranking_history->ranking = (count($ranking) == 0) ? 0 : $ranking[0]->rank;
                    $ranking_histories[] = $ranking_history;
                }
            }
        }
        $result['total_point'] = $total_point;
        $result['month_ranking'] = $month_ranking;
        $result['ranking_histories'] = $ranking_histories;
        $result['need_point'] = $point_gap;
        $result['user_total_cnt'] = User::where('app', $app)->get()->count();

        return $this->response->set_response(0, $result);
    }

    //마이페이지 Point/Ranking -> graph
    public function point_ranking_graph(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string',
                'year_month' => 'required|string'
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

        $app = $request->app;
        $user = $request->user();
        $year_month = explode('-', $request->year_month);
        $year = $year_month[0];
        $month = $year_month[1];

        // 1 입력받은 년-월 랭킹 그래프 그리기용 랭킹 배열
        $util = new Util();
        $start = Carbon::createFromFormat('Y-m', $year . '-' . $month)->firstOfMonth(); // 해당 년월의 1일
        $mondays = $util->getMondays($year, $month);  // 월요일 배열 생성


        // 해당 월 랭킹 없으면 -2001
        $check = DB::select("select rank,user_id,total from
                        (select @vRank := @vRank +1 as rank,user_id,total
                        from (
                        select user_id,sum(score) as total
                        from user_score_logs
                        JOIN users on users.id = user_score_logs.user_id
                        where year(user_score_logs.created_at) = {$year}
                        and month(user_score_logs.created_at) = {$month}
                        and user_score_logs.app = '{$app}'
                        and users.deleted_at is null
                        group by user_score_logs.user_id
                        order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                        )as cnt where user_id = {$user->id};
                ");

        if (count($check) == 0) {
            return $this->response->set_response(-2001, null);
        }

        // 미래 월 입력시 거름 =>데이터없음 [-2001]
        if (Carbon::now()->firstOfMonth() < $start) {
            return $this->response->set_response(-2001, null);
        }

        //현재월일 경우 오늘 랭킹도 배열에 추가하고 끝냄
        if (Carbon::now()->firstOfMonth() == $start) {
            $now_year = Carbon::now()->year;
            $now_month = Carbon::now()->month;
            //이번달 랭킹 검색
            $user_ranking = DB::select("select rank,user_id,total from
                        (select @vRank := @vRank +1 as rank,user_id,total
                        from (
                        select user_id,sum(score) as total
                        from user_score_logs
                        JOIN users on users.id = user_score_logs.user_id
                        where year(user_score_logs.created_at) = {$now_year}
                        and month(user_score_logs.created_at) = {$now_month}
                        and user_score_logs.app = '{$app}'
                        and users.deleted_at is null
                        group by user_score_logs.user_id
                        order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                        )as cnt where user_id = {$user->id};
                ");
            if (count($user_ranking) != 0) {
                $month_ranking = $user_ranking[0]->rank;
            } else { // 랭킹 없으면 0
                $month_ranking = 0;
            }

            //현재월검색에다가 오늘이 1일이면 랭킹 초기화한 날임으로 빈배열 return
            if (Carbon::now()->format('j') == '1') {
                $ranking_histories = [];
            } else {
                // 1일 0위 초기값 setting
                $day1_ranking_0 = new \stdClass();
                $day1_ranking_0->date = '1';
                $day1_ranking_0->ranking = 0;
                $ranking_histories[] = $day1_ranking_0;
                foreach ($mondays as $monday) {
                    $ranking_history = new \stdClass();
                    if ($monday >= Carbon::now()->startOfDay()) {  //오늘 랭킹까지 배열에 추가 후 반복문 break
                        $now = Carbon::now();
                        $ranking_history->date = $now->format('j');
                        $ranking_history->ranking = $month_ranking;
                        $ranking_histories[] = $ranking_history;
                        break;
                    } else {
                        $ranking_history->date = $monday->format('j');
                        $ranking = DB::select("select rank from
                        (select @vRank := @vRank +1 as rank,user_id,total
                        from (
                        select user_id,sum(score) as total
                        from user_score_logs
                        JOIN users on users.id = user_score_logs.user_id
                        where user_score_logs.created_at > '{$start}'
                        and user_score_logs.created_at < '{$monday}'
                        and user_score_logs.app = '{$app}'
                        and users.deleted_at is null
                        group by user_score_logs.user_id
                        order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                        )as cnt where user_id = {$user->id};
                    ");
                        $ranking_history->ranking = (count($ranking) == 0) ? 0 : $ranking[0]->rank;
                        $ranking_histories[] = $ranking_history;
                    }
                }
            }
        } else {
            // 1일 0위 초기값 setting
            $day1_ranking_0 = new \stdClass();
            $day1_ranking_0->date = '1';
            $day1_ranking_0->ranking = 0;
            $ranking_histories[] = $day1_ranking_0;
            foreach ($mondays as $monday) {
                $ranking_history = new \stdClass();
                $ranking_history->date = $monday->format('j');
                $ranking = DB::select("select rank from
                    (select @vRank := @vRank +1 as rank,user_id,total
                    from (
                    select user_id,sum(score) as total
                    from user_score_logs
                    JOIN users on users.id = user_score_logs.user_id
                    where user_score_logs.created_at > '{$start}'
                    and user_score_logs.created_at < '{$monday}'
                    and user_score_logs.app = '{$app}'
                    and users.deleted_at is null
                    group by user_score_logs.user_id
                    order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                    )as cnt where user_id = {$user->id};
                    ");
                $ranking_history->ranking = (count($ranking) == 0) ? 0 : $ranking[0]->rank;
                $ranking_histories[] = $ranking_history;
            }
        }

        // 2 이 달의 랭킹
        $ranking = DB::select("select rank from
                (select @vRank := @vRank +1 as rank,user_id,total
                from (
                select user_id,sum(score) as total
                from user_score_logs
                JOIN users on users.id = user_score_logs.user_id
                where year(user_score_logs.created_at) = {$year}
                and month(user_score_logs.created_at) = {$month}
                and user_score_logs.app = '{$app}'
                and users.deleted_at is null
                group by user_score_logs.user_id
                order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                )as cnt where user_id = {$user->id};
            ");
        if (count($ranking) != 0) {
            $month_ranking = $ranking[0]->rank;
        } else {
            $month_ranking = 0;
        }
        $result['ranking_histories'] = $ranking_histories;
        $result['month_ranking'] = $month_ranking;

        return $this->response->set_response(0, $result);
    }

    // 마이페이지 Point/Ranking -> point history
    public function point_log(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string',
                'next' => 'required|integer'
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

        $app = $request->app;
        $user = $request->user();
        $next = $request->input('next', 0);
        $page_count = 10; //10개씩
        $next++;

        $logs = UserScoreLog::where('app', $app)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->Paginate($page_count, ['*'], 'next', $next);

        if ($logs->count() == 0) {
            return $this->response->set_response(-2001, null);
        }
        // set next_page
        if (!$logs->hasMorePages()) {
            $result['next'] = -1;
        } else {
            $result['next'] = $next;
        }

        //parsing
        foreach ($logs as $log) {
            $history = new \stdClass();
            if ($log->type == 'I') {
                $history->type = '하트보상';
                $history->name = '';
            } elseif ($log->type == 'S') {
                $history->type = '스트리밍';
                $history->name = Music::select('title')->find($log->music_id)->title;
            } elseif ($log->type == 'B') {
                $history->type = '팬피드 작성';
                $history->name = Board::select('title')->find($log->board_id)->title;
            }
            $history->point = $log->score;
            $history->created_at = strtotime($log->created_at);
            $histories[] = $history;
        }

        $result['point_histories'] = $histories;

        return $this->response->set_response(0, $result);
    }


    // 마이페이지 activity_log
    public function activity_log(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string',
                'type' => 'string',   // S 스트리밍, B 게시글 ,C 댓글 , I ,하트 , A 구매 ,
                'next' => 'required|integer'
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
        $app = $request->app;
        $user = $request->user();
        $type = $request->input('type', 'ALL');
        $next = $request->next;

        $page_count = 3; // 3개씩


        if (in_array($type, ['S', 'ALL'])) {
            $logs = UserScoreLog::where('app', $app)
                ->where('user_id', $user->id)
                ->where('type', 'S')
                ->orderByDesc('created_at')
                ->Paginate($page_count, ['*'], 'next');

            if ($logs->count() == 0) {
                return $this->response->set_response(-2001, null);
            }
            //parsing
            foreach ($logs as $log) {
                $history = new \stdClass();
                $history->type = '스트리밍';
                $history->name = Music::select('name')->find('id', $logs->music_id)->name;
                $history->created_at = $log->created_at;
                $histories[] = $history;
            }
        } elseif (in_array($type, ['S', 'B'])) {
            $logs = UserScoreLog::where('app', $app)
                ->where('user_id', $user->id)
                ->where('type', 'B')
                ->orderByDesc('created_at')
                ->Paginate($page_count, ['*'], 'next');

            if ($logs->count() == 0) {
                return $this->response->set_response(-2001, null);
            }
            //parsing
            foreach ($logs as $log) {
                $history = new \stdClass();
                $history->type = '팬피드 작성';
                $history->name = Board::select('title')->find('id', $logs->board_id)->title;
                $history->created_at = $log->created_at;
                $histories[] = $history;
            }
        } elseif (in_array($type, ['I', 'ALL'])) {
            $logs = UserItem::where('app', $app)
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->Paginate($page_count, ['*'], 'next');

            if ($logs->count() == 0) {
                return $this->response->set_response(-2001, null);
            }
            //parsing
            foreach ($logs as $log) {
                $history = new \stdClass();
                $history->type = $log->type == 'B' ? $log->item_count . '개 사용' : '';
                $history->name = Board::select('contents')->find($log->board_id)->contents;
                $history->created_at = $log->created_at;
                $histories[] = $history;
            }
        } elseif (in_array($type, ['C', 'ALL'])) {
            $logs = Comment::where('app', $app)
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->Paginate($page_count, ['*'], 'next');

            if ($logs->count() == 0) {
                return $this->response->set_response(-2001, null);
            }

            //parsing
            foreach ($logs as $log) {
                $history = new \stdClass();
                $history->type = '댓글 작성';
                $history->name = $log->comment;
                $history->point = $log->score;
                $history->created_at = $log->created_at;
                $histories[] = $history;
            }
        } else { // type = A 상품구매 로그 없음
            return $this->response->set_response(-2001, null);
        }

        // set next_page
        if (!$logs->hasMorePages()) {
            $result['next'] = -1;
        } else {
            if ($next) {
                $result['next'] = $next + 1;
            } else {
                $result['next'] = 2;
            }
        }

        $result['histories'] = $histories;

        return $this->response->set_response(0, $result);
    }

    // 마이페이지 setting
    public function setting(Request $request, $user_id)
    {
        try {
            $request->validate([
                'app' => 'required|string'
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
        $user = $request->user();
    }

    public function ranking_v5(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'string',
                'type' => 'string',
                'next' => 'integer',
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
            'app' => $request->input('app', 'fantaholic'),
            'type' => $request->input('type', 'W'),
            'next' => $request->input('next', 0)
        ];


        \Illuminate\Support\Facades\Log::debug(__METHOD__ . ' - params - ' . json_encode($params));
        if ($params['next'] == -1) {
            return $this->response->set_response(-2001, null);
        }

        $app = $request->input('app', 'fantaholic'); //앱이름
        $user = Auth('api')->user(); // 유저정보 비로그인시 null
        $result = [];
        $my_ranking = new \stdClass();

        // $start = 랭킹 검색 범위 시작점
        // $end = 랭킹 검색 범위 끝
        $start = $params['next'];
        if ($start == 0) {
            $end = $start + 13;
        } else {
            $end = $start + 10;
        }

        if ($params['type'] == 'C') { //type = C   ==> 현재 월 실시간 랭킹
            $start_period = Carbon::now()->startOfMonth();
            $end_period = Carbon::now();
            $result['period '] = $start_period->toDateTimeString() . ' ~ ' . $end_period->toDateTimeString();
        } elseif ($params['type'] == 'W') { //type = W ==> 저번주 월요일부터 일요일까지
            $start_period = Carbon::now()->addWeeks(-1)->startOfWeek();
            $end_period = Carbon::now()->addWeeks(-1)->endOfWeek();
            $result['period '] = $start_period->toDateTimeString() . ' ~ ' . $end_period->toDateTimeString();
        } elseif ($params['type'] == 'M') { //type = M ==> 전달 1일부터 마지막일까지
            $start_period = Carbon::now()->addMonths(-1)->startOfMonth();
            $end_period = Carbon::now()->addMonths(-1)->endOfMonth();
            $result['period '] = $start_period->toDateTimeString() . ' ~ ' . $end_period->toDateTimeString();
        } else { //type = M ==> 작년 1월 1일부터 마지막일까지
            $start_period = Carbon::now()->addYears(-1)->startOfYear();
            $end_period = Carbon::now()->addYears(-1)->endOfYear();
            $result['period '] = $start_period->toDateTimeString() . ' ~ ' . $end_period->toDateTimeString();
        }

        //유저 랭킹정보
        if ($user == null) { //비로그인유저
            $my_ranking->id = -1;
            $my_ranking->rank = -1;
            $my_ranking->score = -1;
            $my_ranking->nickname = "";
            $my_ranking->profile_photo_url = "";

        } else {
            $user_ranking = DB::select("select rank,user_id,total,profile_photo_url from
                    (select @vRank := @vRank +1 as rank,user_id,total,profile_photo_url
                    from (
                    select user_id,sum(score) as total , profile_photo_url
                    from user_score_logs
                    JOIN users on users.id = user_score_logs.user_id
                    where user_score_logs.created_at > '{$start_period}'
                    and user_score_logs.created_at < '{$end_period}'
                    and user_score_logs.app = '{$app}'
                    and users.deleted_at is null
                    group by user_score_logs.user_id
                    order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                    )as cnt where user_id = {$user->id}
                 ");
            if (count($user_ranking) != 0) {
                $my_ranking->id = $user->id;
                $my_ranking->rank = $user_ranking[0]->rank;
                $my_ranking->score = $user_ranking[0]->total;
                $my_ranking->nickname = $user->nickname;
                $my_ranking->profile_photo_url = ($user->profile_photo_url != null) ? $user->profile_photo_url : "";
            } else { //유저 랭킹정보가 없을경우
                $my_ranking->id = -1;
                $my_ranking->rank = -1;
                $my_ranking->scoe = -1;
                $my_ranking->nickname = "";
                $my_ranking->profile_photo_url = "";
            }
        }

        $result['my_ranking'] = $my_ranking;

        //랭킹 리스트
        $ranking_list = DB::select("select id,rank,score,nickname,profile_photo_url
                    from (select user_id as id,@vRank := @vRank +1 as rank,total as score,nickname,profile_photo_url
                    from (
                    select user_id,sum(score) as total,users.nickname as nickname ,users.profile_photo_url as profile_photo_url
                    from user_score_logs
                    JOIN users on users.id = user_score_logs.user_id
                    where user_score_logs.created_at > '{$start_period}'
                    and user_score_logs.created_at < '{$end_period}'
                    and user_score_logs.app = '{$app}'
                    and users.deleted_at is null
                    group by user_score_logs.user_id
                    order by sum(user_score_logs.score) desc,user_score_logs.updated_at asc) as p, (select @vRank :=0) as r
                    ) as cnt where rank >= {$start} and rank <= {$end}");
        $count = count($ranking_list);

        foreach ($ranking_list as $ranking) {
            if ($ranking->profile_photo_url == null) {
                $ranking->profile_photo_url = "";
            }
        }
        if ($start == 0) { //첫페이지만 랭킹 1,2,3 위 표시
            $top3 = array_slice($ranking_list, 0, 3);
            $rankings = array_slice($ranking_list, 3, 10);
            if ($count > 13) {
                $result['next'] = $params['next'] + 13;
            } elseif ($count <= 13 && $count >= 0) {
                $result['next'] = -1;
            }
        } else {
            $top3 = [];
            $rankings = array_slice($ranking_list, 0, 10);

            if ($count >= 10) {
                $result['next'] = $params['next'] + 10;
            } elseif ($count < 10 && $count >= 0) {
                $result['next'] = -1;
            }
        }
        $result['cdn_url'] = $this->config[$app]['cdn'];
        $result['top3'] = $top3;
        $result['rankings'] = $rankings;
        return $this->response->set_response(0, $result);

    }

    public function profile_photo_update(Request $request)
    {
        \Illuminate\Support\Facades\Log::info(__METHOD__ . ' - params - ' . json_encode($request->allFiles()));

        try {
            $validator = $this->validate($request, [
                'profile_photo' => 'required|image',
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


        $user = $request->user();

        $util = new Util();
        //profile_photo 파일 cdn 저장
        if ($request->hasFile('profile_photo')) {
            $path = 'images/user_profile_photos/';

            if (!File::exists(public_path() . '/' . $path)) {
                \File::makeDirectory(public_path() . '/' . $path);
            }

            $filename = $util->SaveThumbnailAzure($request->file('profile_photo'), $path);
            $params['profile_photo_url'] = "/" . $path . $filename;
        }

        $user->update([
            'profile_photo_url' => $params['profile_photo_url']
        ]);

        return $this->response->set_response(0, ['profile_photo_url' => $params['profile_photo_url']]);
    }

    // 성인여부 리턴
    protected function isAdult($birth_day)
    {
        $birth_year = substr($birth_day, 0, 4);
        $birth_month = substr($birth_day, 4, 2);
        $birth_day = substr($birth_day, 6, 2);

        $birth_timestamp = Carbon::parse(sprintf("%s-%s-%s 0:0:0",
            $birth_year,
            $birth_month,
            $birth_day
        ), 'Asia/Seoul')->timestamp;

        $target_timestamp = Carbon::now('Asia/Seoul')->subYears(19)->timestamp;

        if ($target_timestamp > $birth_timestamp) {

            return 1;

        } else {

            return 0;
        }
    }
}
