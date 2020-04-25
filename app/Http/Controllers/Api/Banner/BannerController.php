<?php

namespace App\Http\Controllers\Api\Banner;

use Illuminate\Http\Request;
use Google\ApiCore\ValidationException;
use App\Lib\Log;
use App\Banner;
use App\Lib\Response;
use App\Http\Controllers\Controller as baseController;

class BannerController extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function banner(Request $request){
        try {
            $validator = $this->validate($request, [
                'app'           =>  'string',
                'board'          =>  'string',
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
            'app'   =>  $request->input('app'),
            'board'  =>  $request->input('board')
        ];

        //상단 배너
        $banners = Banner::where('app',$params['app'])
            ->where('board',$params['board'])
            ->orderBy('order_num','asc')->get();

        $result['cdn_url'] = app('config')['celeb'][$params['app']]['cdn'];
        $result['banners'] = $banners;

        return $this->response->set_response(0,$result);
    }
}
