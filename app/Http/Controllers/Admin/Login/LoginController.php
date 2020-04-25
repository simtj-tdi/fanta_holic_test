<?php
namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller as BaseController;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    public function __construct()
    {
//        $this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        // 아이디와 패스워드가 같으면 패스워드 변경 창으로 이동
        if ($request->input('email') == $request->input('password')) {
            return response()->json([
                'error' => 2,
                'message' => '패스워드를 변경해주세요'
            ], 422);
        }

        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return response()->json([
                'error' => 1,
                'message' => '아이디 비밀번호를 다시 한 번 확인해주세요'
            ], 422);
        }

        $user = Auth::user();

        $tokenResult = $user->createToken('Admin Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addDay();
        $token->save();

        $request->session()->put('access_token', $tokenResult->accessToken);
        $request->session()->put('token_type', 'Bearer');
        $request->session()->put('expires_at', Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString());

        return response()->json([]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->guest('/admin/login');
    }

    public function form()
    {
        return view('login.register')->with([
            'title' => '사용자 등록',
            'register_menu' => 'active'
        ]);
    }

    public function store(Request $request)
    {
        exit;
//        $exist = DB::table('users')
//            ->where('email', $request->get('email'))
//            ->get()
//            ->count();
//
//        if ($exist > 0) {
//            return response()->json([
//                'message' => 'error',
//                'errors' => ['email' => ['이미 등록된 계정 입니다.']]
//            ], 422);
//        }
////
////        $this->validate($request, [
//            'app' => 'required|string',
//            'name' => 'required|string',
//            'email' => 'required|string',
//            'password' => 'required|min:4',
//            'level' => 'required|integer',
//        ], [
//            'name.required' => '담당자명을 입력 해주세요.',
//            'email.required' => '로그인 계정을 입력 해주세요.',
//            'password.required' => '비밀번호를 입력 해주세요.',
//        ]);





        DB::table('users')
            ->insert([
                'app' => $request->input('app','BTS'),
                'name' => $request->input('name','bts'),
                'email' => $request->input('email','bts'),
                'password' => Hash::make($request->input('password','1234qwer')),
//                'level' => $request->input('level',0),
                'created_at' => Carbon::now('Asia/Seoul')->toDateTimeString()
            ]);

        return response()->json(['message' => 'success']);
    }

    public function password($email)
    {
        return view('login.password')->with([
            'email' => $email
        ]);
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|min:4',
        ], [
            'email.required' => '로그인 계정을 입력 해주세요.',
            'password.required' => '비밀번호를 입력 해주세요.',
        ]);

        DB::table('users')
            ->where('email', $request->input('email'))
            ->update([
                'password' => Hash::make($request->input('password')),
            ]);

        return response()->json(['message' => 'success']);
    }

}
