<?php

namespace App\Http\Controllers\Api\Notice;

use App\Lib\LobbyClassv6;
use App\Lib\Response;
use App\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as baseController;
use App\Lib\Log;

class Controller extends baseController
{

    protected $response;
    protected $redis;

    public function __construct()
    {
        $this->response = new Response();
        $this->redis = app('redis');
        $this->cache = app('cache');
    }

    public function notice_list(Request $request){
        try {
            $validator = $this->validate($request, [
                'app'           =>  'required|string',
                'next'          =>  'required|integer'
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

        $user = Auth('api')->user();

        $params = [
            'app'   =>  $request->input('app'),
            'next'  =>  $request->input('next')
        ];

        $page_count = 10;

        $notices = Notice::where('app',$params['app'])
            ->where('type','A')
            ->when($user,function($query) use($user,$params){
                $query->orwhere(function($query) use($user,$params){
                    $query->where('type','P')->where('user_id',$user->id)->where('app',$params['app']);
                });
            })
            ->orderBy('created_at','desc')
            ->Paginate($page_count,['*'],'next');

        if($notices->count() == 0){
            $this->response->set_response(-2001,null);
        }

        // set next_page
        if (!$notices->hasMorePages()) {
            $page['next_page'] = -1;
        } else {
            if ($params['next_page']) {
                $page['next_page'] = $params['next_page'] + 1;
            } else {
                $page['next_page'] = 2;
            }
        }

        $lobbyClass= new LobbyClassv6();
        $notices = $lobbyClass->notice_parsing($notices,$user);
        $result['cdn_url']  =   config('celeb')[$params['app']]['cdn'];
        $result['next'] =   $page['next_page'];
        $result['notices']  =   $notices;

        return $this->response->set_response(0,$result);
    }
}
