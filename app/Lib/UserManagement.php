<?php

namespace App\Lib;

use App\Standard;
use App\User;
use App\UserLoginHistory;
use App\UserScoreLog;
use Elasticsearch\ClientBuilder;
use Carbon\Carbon;

class UserManagement
{
    protected $redis;
    //앱이름,
    //유저,
    //점수,
    //점수지급타입('I:아이템사용 S:스트리밍 A:인앱샵,상품구매 B:팬피드작성'),
    // S[스트리밍]일때 음원 id,
    // B[게시물작성]일떄 게시물 id ,
    // I [아이템사용시] 해당 게시물 id
    public function addscore($app, $user, $score, $type, $music_id = null, $board_id = null, $item_board_id)
    {
        //관리자는 점수안줌 => 랭킹에 반영안함
        $is_admin = $user->is_admin == 1 ? true : false;

        if (!$is_admin) {
            //점수 로그
            UserScoreLog::create([
                'app' => $app,
                'user_id' => $user->id,
                'score' => $score,
                'type' => $type,
                'music_id' => $music_id,
                'board_id' => $board_id
            ]);
        }
        return;
    }


    public function day_login_check($app, $user)
    {
        $chk = UserLoginHistory::where('user_id', $user->id)
            ->where('app',$app)
            ->whereDate('created_at', '=', date('Y-m-d'))->get()->count();

        return (!empty($chk)) ? true : false;
    }


    //아이템 지급
    public function additem($app, $user_id, $number)
    {
        $user = User::where('id', $user_id)->first();
        $user->item_count += $number;
        $user->save();
        return $user->item_count;
    }

}




