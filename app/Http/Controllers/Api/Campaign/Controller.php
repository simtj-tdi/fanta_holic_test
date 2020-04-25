<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Campaign;
use App\Exceptions\ErrorCodes;
use App\Http\Traits\PushTrait;
use App\Lib\Log;
use App\Lib\Response;
use App\Lib\UserManagement;
use App\Push;
use App\UserReward;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as baseController;
use Illuminate\Support\Facades\Artisan;

class Controller extends baseController
{
    use PushTrait;
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function campaign_reward(Request $request){

    }

    //멜론 스트리밍 푸시 이후 보상
    public function push_reward(Request $request)
    {
        $validator = $this->validate($request,[
            'app'   =>  'required|string',
            'push_id'   =>  'required',
            'user_id'   =>  'required'
        ]);

        $params = [
            'app'   =>  $request->input('app'),
            'push_id'   =>  $request->input('push_id'),
            'user_id'   =>  $request->input('user_id')
        ];

        $redis = app('redis');
        $app = $params['app'];

        //push 검색
        $push = Push::find($params['push_id']);


        //멜론 스트리밍 푸시
        if($push->action  == 'S') {
            //campaign 검색
            $campaign = Campaign::find($push->campaign_id);
            $reward_count = $campaign->item_count;
            // 참여 했던 충전소 인지 확인
            if ($campaign->repeat > 0) {
                // 반복형
                $check = UserReward::where('app',$app)
                    ->where('user_id',$params['user_id'])
                    ->where('campaign_id',$campaign->id)
                    ->where('created_at','>',Carbon::now()->addMinutes(($campaign->repeat * -1)))
                    ->get();
            } else {
                // 일반형
                $check = UserReward::where('app',$app)
                    ->where('user_id',$params['user_id'])
                    ->where('campaign_id',$campaign->id)
                    ->get();
            }

            if (count($check) > 0) {
                // 이미 참여한 충전소
                return $this->response->set_response(-5002,null);
            }
            UserReward::created([
                'app'   =>  $app,
                'campaign_id'   =>  $campaign->id,
                'user_id'   =>  $params['user_id'],
                'log_type'  =>  'C',
                'description'=>'캠페인 보상 지급',
                'item_count'=>$reward_count,
            ]);

            $user_management = new UserManagement();
            $user_item_count_result =$user_management->additem($params['app'],$params['user_id'],$reward_count);
            Push::create([
                'app'   =>  $params['app'],
                'campaign_id'   =>  $push->campaign_id,
                'batch_type'    =>  'P',
                'managed_type'  =>  'R',
                'user_id'       =>  $params['user_id'],
                'title'         =>  $campaign->push_title,
                'content'       =>  $campaign->psuh_message,
                'tick'          =>  $campaign->push_tick,
                'push_type'     =>  'T',
                'action'        =>  'A',
                'state'         =>  'R',
                'start_date'    =>  Carbon::now(),
            ]);
            // 즉시발송
            Artisan::call('push:worker P');
            return $this->response->set_response(0,[
                'reward_count'  =>  $reward_count,
                'user_item_count' => $user_item_count_result
            ]);
        }else{
            return $this->response->set_response(0,null);
        }
    } 


    //캠페인 on/off
    public function state_update(Request $request, $campaign_id){
        try{
            $campaign = Campaign::find($campaign_id);
            if($campaign ==null){
                return response()->json([
                    'result'     =>  'fail',
                    'code'       =>  0,
                    'message'    =>  'There are no apps for such packages',
                    'data'       =>  new \stdClass()
                ]);
            }else{
                $campaign->update([
                    'state' =>  $request->input('state')
                ]);
            }
        } catch (QueryException $e) {

            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::DATABASE_EXCEPTION,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 500);
        }


        return response()->json([
            'result' => 'success',
            'code' => 0,
            'message' => 'Success',
            'data' => new \stdClass(),
        ], 200);
    }
}
