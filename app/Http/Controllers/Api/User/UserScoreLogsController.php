<?php

namespace App\Http\Controllers\Api\User;

use App\Board;
use App\BoardLike;
use App\Comment;
use App\Lib\Log;
use App\Lib\Response;
use App\Music;
use App\UserItem;
use App\UserScoreLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class UserScoreLogsController extends Controller
{
    //
    public function __construct()
    {
        $this->response = new Response();
    }

    public function index(Request $request){
        try {
            $request->validate([
                'app'      =>  'required|string',
            ]);
        }catch (ValidationException $e) {
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
        $user= $request->user();

        $histories['streaming'] = UserScoreLog::where('app',$app)
            ->where('user_id',$user->id)
            ->where('type','S')
            ->orderByDesc('created_at')
            ->count();

        $histories['fan'] = UserScoreLog::where('app',$app)
            ->where('user_id',$user->id)
            ->where('type','B')
            ->orderByDesc('created_at')
            ->count();

        /*$histories['items'] = UserItem::where('app',$app)
            ->where('user_id',$user->id)
            ->orderByDesc('created_at')
            ->count();*/

        $histories['items'] = BoardLIke::where('users_id',$user->id)
            ->orderByDesc('created_at')
            ->count();


        $histories['comments'] = Comment::where('comments.app',$app)
            ->join('boards','comments.board_id', '=','boards.id' )
            ->where('comments.user_id',$user->id)
            ->orderByDesc('comments.created_at')
            ->count();

        $histories['buy'] = 0;

        return $this->response->set_response(0,$histories);
    }

    public function show(Request $request, $user_id, $type){
        try {
            $request->validate([
                'app'      =>  'required|string',
//                'type'      => 'string',   // S 스트리밍, B 게시글 ,C 댓글 , I ,하트 , A 구매 ,
                'next'      => 'required|integer'
            ]);
        }catch (ValidationException $e) {
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
        $user= $request->user();
        $next = $request->input('next',0)+1;


        $page_count = 3; // 3개씩

        if(in_array($type,['S','ALL']) ){
            $logs = UserScoreLog::where('app',$app)
                ->where('user_id',$user->id)
                ->where('type','S')
                ->orderByDesc('created_at')
                ->Paginate($page_count,['*'],'next',$next);

            if($logs->count()==0){
                return $this->response->set_response(-2001,null);
            }


            //parsing
            foreach ($logs as $log){

                $music = Music::select(['id','title','thumbnail_url'])->where('id',$log->music_id)->get()->first();
                $history= new \stdClass();
                $history->id = $music->id;
                $history->type = '스트리밍';
                $history->bpard_type = null;
                $history->post = null;
                $history->thumbnail_url = $music->thumbnail_url;
                $history->name = $music->title;
                $history->created_at = Carbon::parse($log->created_at)->diffForHumans();
                $histories[] = $history;
            }

        }elseif (in_array($type,['S','B']) ){
            $logs = UserScoreLog::where('app',$app)
                ->where('user_id',$user->id)
                ->where('type','B')
                ->orderByDesc('created_at')
                ->Paginate($page_count,['*'],'next',$next);

            if($logs->count()==0){
                return $this->response->set_response(-2001,null);
            }
            //parsing
            foreach ($logs as $log){
                $board = Board::select(['post','id','contents','title','thumbnail_url','type'])->find($log->board_id);
                $history= new \stdClass();
                $history->id   = $board->id;
                $history->type = '팬피드 작성';
                $history->board_type = $board->type;
                $history->post = $board->post;
                $history->name = $board->title;
                $history->thumbnail_url = $board->thumbnail_url;
                $history->created_at = Carbon::parse($log->created_at)->diffForHumans();
                $histories[] = $history;
            }
        }elseif (in_array($type,['I','ALL']) ) {
            $logs = BoardLike::where('users_id',$user->id)
                ->orderByDesc('created_at')
                ->Paginate($page_count,['*'],'next',$next);
            //dd($logs);
            if($logs->count()==0){
                return $this->response->set_response(-2001,null);
            }
            //parsing
            foreach ($logs as $log){
                $board = Board::select(['post','id','contents','thumbnail_url','type'])->find($log->boards_id);
                $like = BoardLike::where('boards_id',  $log->boards_id)->count();
                $history= new \stdClass();
                $history->id   = $board['id'];
                $history->type = '좋아요';
                $history->post = $board['post'];
                $history->board_type = $board['type'];
                $history->name = $board['contents'];
                $history->thumbnail_url = $board['thumbnail_url'];
                $history->ori_thumbnail_url = $board['ori_thumbnail_url'];
                $history->like = $like;
                $history->created_at = Carbon::parse($log->created_at)->diffForHumans();
                $histories[] = $history;
            }
        }elseif (in_array($type,['C','ALL']) ){
            $logs = Comment::where('comments.app',$app)
                ->join('boards','boards.id', '=','comments.board_id' )
                ->where('comments.user_id',$user->id)
                ->orderByDesc('comments.created_at')
                ->Paginate($page_count,['*'],'next',$next);

            if($logs->count()==0){
                return $this->response->set_response(-2001,null);
            }

            //parsing
            foreach ($logs as $log){
                $history= new \stdClass();
                $history->id   = $log->id;
                $history->type = '댓글 작성';
                $history->board_type = $log->type;
                $history->post = $log->post;
                $history->name = $log->comment;
                $history->thumbnail_url = $log->thumbnail_url;
                $history->ori_thumbnail_url = $log['ori_thumbnail_url'];
                $history->created_at =  Carbon::parse($log->created_at)->diffForHumans();
                $histories[] = $history;
            }
        }else{ // type = A 상품구매 로그 없음
            return $this->response->set_response(-2001,null);
        }

        // set next_page
        if (!$logs->hasMorePages()) {
            $result['next'] = -1;
        } else {
            $result['next'] = $next;
        }

        $result['histories'] = $histories;

        return $this->response->set_response(0,$result);
    }
}
