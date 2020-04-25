<?php

namespace App\Http\Controllers\Admin\User;

use App\Device;
use App\User;
use Elasticsearch\ClientBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['http://elastic:aktlakfh!@34@52.231.207.203:9200'])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();
    }
    public function index(Request $request){
        //todo 앱구분

        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }

        $params = [
            'type'  =>  $request->input('type',''),
            'user_type' => $request->input('user_type', 'all'),
            'search_type' => $request->input('search_type'),
            'search_value' => $request->input('search_value'),
            'start_date' => $request->input('start_date', Carbon::now()->addDays(-7)->toDateString()),
            'end_date' => $request->input('end_date', Carbon::now()->toDateString())
        ];

        $total = User::where('app',$app)->when($params['search_value'], function ($query) use ($params) {
                return $query->where($params['search_type'], 'like', '%'.$params['search_value'].'%');
            })
            ->whereBetween('created_at', [$params['start_date']." 00:00:00", $params['end_date']." 23:59:59"])
            ->get()
            ->count();

        $rows = User::where('app',$app)->when($params['user_type'], function ($query) use ($params) {
                if ($params['user_type'] == 'user') {
                    return $query->where('email', '!=', '');
                } elseif ($params['user_type'] == 'none') {
                    return $query->wherenull('email');
                } else {
                    return $query;
                }
            })
            ->when($params['search_value'], function ($query) use ($params) {
                return $query->where($params['search_type'], 'like', '%'.$params['search_value'].'%');
            })
            ->whereBetween('created_at', [$params['start_date']." 00:00:00", $params['end_date']." 23:59:59"])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
//        dd($rows);
        return view('users.index')->with([
            'title' => '유저 관리',
            'user_menu' => 'active',
            'params' => $params,
            'rows' => $rows,
            'total_count' => $total
        ]);
    }
    // 유저 상세 정보
    public function view(Request $request,$id)
    {
        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $user = User::where('users.app',$app)->with('devices')
            ->where('users.id',$id)->get()->last();

//        $devices = Device::where('user_id',$id)->get();

        return view('users.view')->with([
            'title' => '유저 관리',
            'user_menu' => 'active',
            'user' => $user,
//            'devices'=>$devices
        ]);
    }

    public function update(Request $request,$id)
    {
        $user = $request->user();

        if($user != null){
            $app = $user->app;
        }else{
            $app='pinxy';
        }
        $params = [
            'black' => $request->input('black'),
        ];

        User::where('app',$app)->where('id',$id)->get()->last()->update([
            'black' =>  $params['black']
        ]);
        return redirect('/admin/users/'.$id);
    }
}
