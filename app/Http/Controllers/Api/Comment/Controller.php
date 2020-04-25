<?php

namespace App\Http\Controllers\Api\Comment;

use App\Board;
use App\Push;
use App\Lib\LobbyClassv6;
use App\Comment;
use App\Lib\Response;
use App\Lib\Util;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Lib\Log;
use App\Http\Controllers\Controller as baseController;


class Controller extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    //댓글 목록 v3
    public function index_v3(Request $request){
        $validator = $this->validate($request,[
            'board_id'  =>  'required',
            'app'       =>  'required'
        ]);
        $params = [
            'app'       =>  $request->input('app'),
            'board_id'  =>  (int)$request->input('board_id'),
            'page' => ($request->input('page') == null) ? 1 : $request->input('page'),
        ];

        if($params['page']==-1){
            return $this->response->set_response(-2001,null);
        }

        $user = Auth('api')->user();
        $page_count = 10;  // 한페이지당 댓글수

        //해당 게시물 댓글 수 (대댓글 포함)
        $total = Board::where('id',$params['board_id'])->get()->last()->comments()->count();
        //댓글 없으면 -2001
        if($total == 0){
            return $this->response->set_response(-2001,null);
        }

        //셀럽댓글 상단에 표시함으로 제외함
        $query = Comment::where('board_id',$params['board_id'])
            ->where('parent_id',null)->where('type','!=','C')
            ->orderBy('id','desc');
        $comments = $query->paginate($page_count);

        // 다음페이지 있는 확인 후 있으면 page +1  없으면 -1
        if($comments->hasmorePages()){
            $next_page = $params['page']+1;
        }else{
            $next_page = -1;
        }
        $comments = $comments->items();

        //로그인 유저일시 좋아요/신고 했는지 확인 (is_like , is_report)
        $lobbyClass = new LobbyClassv6();
        $comments = $lobbyClass->comment_parsing_v3($comments,$params,$user);

        $data = [
            'next_page' =>  $next_page,
            'total' =>  $total,
            'comments'  =>  $comments
        ];
        return $this->response->set_response(0,$data);
    }

    //대댓글 목록
    public function reply_v3(Request $request){
        $validator = $this->validate($request,[
            'app'       =>  'required',
            'parent_id' =>  'required'
        ]);

        $params = [
            'app'       =>  $request->input('app'),
            'parent_id' =>  $request->input('parent_id'),
            'page' => $request->input('page', 1),
        ];

        if($params['page']==-1){
            return $this->response->set_response(-2001,null);
        }
        $user = $request->user();

        $query = Comment::where('parent_id',$params['parent_id'])->orderBy('id','desc');

        //대댓글 수
        $total = $query->count();

        //3개씩  => 페이지 처리 없이 다달라고함
//        $page_count = 3;
//        $comments = $query->paginate($page_count);
        $comments = $query->get();

        $lobbyClass = new LobbyClassv6();
        $comments = $lobbyClass->comment_parsing_v3($comments,$params,$user);

        $data = [
            'total' =>  $total,
            'comments'  =>  $comments
        ];

        return $this->response->set_response(0,$data);
    }

    //일반 댓글 저장
    public function store(Request $request){
        try {
            $validator = $this->validate($request, [
                'board_id'  =>  'required',
                'app'       =>  'required',
                'comment'   =>  'required|string'
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
        $util= new Util();
        $params = [
            'app'   =>  $request->input('app'),
            'board_id'  =>  $request->input("board_id"),
            'user_id'   =>  $request->user()->id,
            'comment'   =>  $util->bannedWordsFiltering($request->input('comment')),
            'parent_id' =>  ($request->input('parent_id',-1) == -1) ? null : $request->input('parent_id'),
            'type'      =>  'N'
        ];

        //삭제되거나 없는 게시물일 경우
        $board = Board::where('id',$params['board_id'])->get();
        if($board->count() ==0){
            return $this->response->set_response(-4003,null);
        }

//        //팬피드이고 자기가쓴 피드가 아닐경우 공지생성 => 안씀
//        if($board[0]->type = 'fanfeed' && $user->id != $board[0]->user_id){
//            Notice::create([
//                'app'   =>  $params['app'],
//                'type'  =>  'P',
//                'managed_type'  =>  'C',
//                'user_id'   =>  $board[0]->user_id,
//                'title' =>  '회원님의 게시물에 댓글이 달렸습니다',
//                'contents'   =>'회원님의 게시물에 댓글이 달렸습니다',
//            ]);
//        }

        if($params['parent_id'] != null){
            $parent_comment = Comment::where('id',$params['parent_id'])->get()->last();
            if($parent_comment == null){
                return $this->response->set_response(-4001,null);
            }

            $comment = Comment::where('id',$params['parent_id'])->get()->last()->replies()->create($params);
            //자기가 쓴댓글에 대댓글은 알림생성 x
//            if($user->id != $parent_comment->user_id ){
//                //대댓글생성 알림
//                Notice::create([
//                    'app'   =>  $params['app'],
//                    'type'  =>  'P',
//                    'managed_type'  =>  'C',
//                    'user_id'   =>  $parent_comment->user_id,
//                    'title' =>  '회원님의 댓글에 답변이 달렸습니다',
//                    'contents'   =>'회원님의 댓글에 답변이 달렸습니다',
//                ]);
//            }
        }else{
            $comment = Comment::create($params);
        }


        $data = [
            'comment_id'    =>  $comment->id,
            'comment'   => $params['comment']
        ];

        return $this->response->set_response(0,$data);
    }
    public function reply_store(Request $request){
        $validator = $this->validate($request,[
            'parent_id' =>  'required',
            'app'       =>  'required',
            'comment'   =>  'required|string'
            ]);
        $user = $request->user();
        $util= new Util();
        $params = [
            'app'   =>  $request->input('app'),
            'comment'   =>  $util->bannedWordsFiltering($request->input('comment')),
            'parent_id' =>  ($request->input('parent_id',-1) == -1) ? null : $request->input('parent_id'),
            'type'      =>  'N'
        ];

        $parent_comment = Comment::where('app',$params['app'])->where('id',$params['parent_id'])->get();

        if($parent_comment->count()==0){
            return $this->response->set_response(-4001,null);
        }

        $comment = $request->user()->comment()->create($params);

        //대댓글 알림 =>
        if($user->id != $parent_comment[0]->user_id ){
            Push::create([
                'app'   =>  $params['app'],
                'batch_type'  =>  'P',
                'managed_type'  =>  'C',
                'user_id'   =>  $parent_comment[0]->user_id,
                'title' =>  '회원님의 댓글에 답변이 달렸습니다',
                'content'   =>'회원님의 댓글에 답변이 달렸습니다',
            ]);
        }

        $data = [
            'comment_id'    =>  $comment->id,
            'comment'   =>  $comment->comment
        ];

        return $this->response->set_response(0,$data);
    }
    public function edit(Request $request,$comment_id){
        $user = Auth('api')->user();
        if(!$user){
            return $this->response->set_response(-3004,null);
        }
        $validator = $this->validate($request,[
            'comment'   =>  'required|string',
            'app'       =>  'required|string'
        ]);

        $util= new Util();

        $params = [
            'app'           =>  $request->input('app'),
            'comment_id'    =>  $comment_id,
            'comment'       =>   $util->bannedWordsFiltering($request->input('comment'))
        ];
        $query = Comment::where('id',$params['comment_id'])->get()->last();

        if($query == null){
            return $this->response->set_response(-4001,null);
        }

        if($query->id == $user->id){ //글 쓴 유저가 맞는지 확인
            if($user->is_admin==1){} //관리자는 수정 가능}
            else{
                return $this->response->set_response(-4002,null);
            }
        }

        $query->update([
            'comment'   =>  $params['comment']
        ]);

        $comment = Comment::find($params['comment_id']);
        $comment->user_id = (string)$comment->user_id;
        $data = [
            'comment' =>    $comment
        ];
        return $this->response->set_response(0,$data);
    }
    public function destroy(Request $request,$comment_id){
        $validator = $this->validate($request,[
            'app'       =>  'required|string'
        ]);
        $params = [
            'app'   =>  $request->input('app'),
            'comment_id'    =>  $comment_id,
        ];

        $query = Comment::where('id',$params['comment_id'])->get()->last();
        if($query == null) {
            return $this->response->set_response(-4001,null);
        }
        if($query->id ==$request->user()->id){ //글 쓴 유저가 맞는지 확인
            if($request->user()->is_admin==1){} //관리자는 수정 가능}
            else{
                return $this->response->set_response(-4002,null);
            }
        }

        $query->replies()->delete();
        $query->delete();
        return $this->response->set_response(0,null);
    }

    public function like(Request $request){
            $validator = $this->validate($request, [
                'comment_id'        => 'required',
            ]);

            $params = [
                'user_id'   =>  $request->user()->id,
                'comment_id'  =>  $request->input('comment_id'),
                'app'         => $request->input('app')
            ];

            $user = $request->user();

            //이미 좋아요 한건지 확인
            $check = $user->userresponsetocomment()->where('user_id',$params['user_id'])->where('comment_id',$params['comment_id'])->count();

            if($check > 0){
                $user->userresponsetocomment()->where('user_id',$params['user_id'])->where('comment_id',$params['comment_id'])->delete();
                $result = false;
            }else{
                $user->userresponsetocomment()->create($params);
                $result = true;
            }
            $like_count = Comment::where('id',$params['comment_id'])->get()->last()->like_count;

            return $this->response->set_response(0,[
                'result'    =>  $result,
                'like_count'    =>      $like_count
            ]);
    }

    public function report(Request $request){
        $validator = $this->validate($request, [
            'comment_id'        => 'required',
        ]);

        $params = [
            'response' => 0,
            'user_id'   =>  $request->user()->id,
            'comment_id'  =>  $request->input('comment_id'),
            'app'         => $request->input('app')
        ];

        $user = $request->user();

        //이미 좋아요 한건지 확인
        $check = $user->userresponsetocomment()->where('user_id',$params['user_id'])->where('comment_id',$params['comment_id'])->where('response',0)->count();

        if($check > 0){
            $user->userresponsetocomment()->where('user_id',$params['user_id'])->where('comment_id',$params['comment_id'])->where('response',0)->delete();
            $result = false;
        }else{
            $user->userresponsetocomment()->create($params);
            $result = true;
        }
        $report_count = Comment::where('id',$params['comment_id'])->get()->last()->report_count;

        return $this->response->set_response(0,[
            'result'    =>  $result,
            'report_count'    =>      $report_count
        ]);
    }
}
