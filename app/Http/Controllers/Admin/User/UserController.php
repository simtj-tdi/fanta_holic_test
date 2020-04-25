<?php

namespace App\Http\Controllers\Admin\User;

use App\Device;
use App\Enums\UserSnsType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as ResponseType;

class UserController extends BaseController
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        //TODO 앱구분


        try {
            $user = $request->user();

            $params = [
                'type' => $request->input('type', ''),
                'userType' => $request->input('userType', 'all'),
                'schType' => $request->input('schType'),
                'schVal' => $request->input('schVal'),
                'startDate' => $request->input('startDate', Carbon::now()->addDays(-7)->toDateString()),
                'endDate' => $request->input('endDate', Carbon::now()->toDateString()),
                'schDateType' => $request->input('schDateType', 'created_at')
            ];
            $params['app'] = $user->app;


            $userQuery = User::getList($params);
            $total = $userQuery->get()->count();
            $rows = $userQuery->paginate(15);

            return view('users.index')->with([
                'title' => '유저 관리',
                'user_menu' => 'active',
                'params' => $params,
                'rows' => $rows,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


// 유저 상세 정보
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if ($user != null) {
            $app = $user->app;
        } else {
            $app = 'pinxy';
        }
        $user = User::where('users.app', $app)->with([
            'devices',
            'userLoginHistory',
            'userItem',
            'board',
            'comment'
        ])->find($id);


        return view('users.view')->with([
            'title' => '유저 관리',
            'user_menu' => 'active',
            'user' => $user,
//            'devices'=>$devices
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if ($user != null) {
            $app = $user->app;
        } else {
            $app = 'pinxy';
        }
        $params = [
            'black' => $request->input('black'),
        ];

        User::where('app', $app)->where('id', $id)->get()->last()->update([
            'black' => $params['black']
        ]);
        return redirect('/admin/users/' . $id);
    }

    public function delete(Request $request, $id)
    {
        $user_id = $id;
        try {
            $devices = Device::where('user_id', $user_id)->get();
            foreach ($devices as $device) {
                $device->delete();
            }
            //토큰 폐기
            $user = User::find($id);

            foreach($user->tokens() as $key=> $token)
            {
                $token->revoke();
            }

            $user->delete();

            if ($request->ajax()) {
                return Response::json([], ResponseType::HTTP_OK);
            } else {
                return Response::redirectTo([], ResponseType::HTTP_OK);
            }
        }
        catch( \Exception $e)
        {
            Log::error(__METHOD__.' - throw exception - '.$e->getTraceAsString());
            if ($request->ajax()) {
                return Response::json([], $e->getCode());
            } else {
                return Response::redirectTo('/admin/users', $e->getCode());
            }
        }


    }
}
