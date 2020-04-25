<?php
namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller as BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    public function index()
    {
        $value['app'] = [
            'all' => '전체',
            'leeseol' => '이설튜브'
        ];
        $value['level'] = [
            0 => 'Master',
            1 => 'Manager',
            2 => 'Uploader'
        ];

        $rows = DB::table('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('login.register')->with([
            'title' => '사용자 등록',
            'register_menu' => 'active',
            'value' => $value,
            'rows' => $rows
        ]);
    }

    public function store(Request $request)
    {
        $exist = DB::table('admin')
            ->where('email', $request->get('email'))
            ->get()
            ->count();

        if ($exist > 0) {
            return response()->json([
                'message' => 'error',
                'errors' => ['email' => ['이미 등록된 계정 입니다.']]
            ], 422);
        }

        $this->validate($request, [
            'app' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|min:4',
            'level' => 'required|integer',
        ], [
            'name.required' => '담당자명을 입력 해주세요.',
            'email.required' => '로그인 계정을 입력 해주세요.',
            'password.required' => '비밀번호를 입력 해주세요.',
        ]);

        DB::table('admin')
            ->insert([
                'app' => $request->input('app'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'level' => $request->input('level'),
                'created_at' => Carbon::now('Asia/Seoul')->toDateTimeString()
            ]);

        return response()->json(['message' => 'success']);
    }
}
