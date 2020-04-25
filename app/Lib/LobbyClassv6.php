<?php

namespace App\Lib;

use App\Comment;
use App\UserItem;
use App\UserResponseToBoard;
use App\UserResponseToComment;
use App\Board;
use App\Follow;
use App\Artist;
use App\UserScoreLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Google\Type\Date;
use Illuminate\Support\Facades\DB;
use Elasticsearch\ClientBuilder;


//시간순
//움짤 안뭉치게

class LobbyClassv6
{
    protected $redis;
    protected $config;

    public function makeCelebPage($app, $next_token = 1)
    {
        // Redis Connection
        if ($next_token == 0) {
            $next_token = 1;
        }

        $page_count = 20;


        $board_select_query = Board::where('app', $app)
            ->where('type', '!=', 'fanfeed')
            ->where('state', 1)
            ->where('post','NOT LIKE','%.%')
//            ->where('post_type','!=','image')->OrWhere('type','=','news')
            ->where('data','!=','[]')
            ->orderby('name', 'asc')
            ->Paginate($page_count, ['*'], 'next_page', $next_token);

        if (!($board_select_query->hasMorePages())) {
            $next_page = -1;
        } else {
            $next_page = $next_token + 1;
        }

        $this->config = app('config')['celeb'][$app];
        $response['cdn_url'] = $this->config['cdn'];
        $response['next_page'] = $next_page;
        $response['body'] = $board_select_query->items();

        // Redis Cache
//        $this->redis = app('redis');
//        $this->redis->set("{$app}:Lobby:V2:page:{$next_token}", json_encode($response));
//        $this->redis->expire("{$app}:Lobby:V2:page:{$next_token}", 3600);

        return $response;
    }

    public function makeListPage($app, $next_token = 1, $type, $user_id, $artist_id, $sns_type)
    {
        // Redis Connection
        if ($next_token == 0) {
            $next_token = 1;
        }

        $page_count = 20;

        $this->user_id = $user_id;
        $this->artist_id = $artist_id;
        $this->sns_type = $sns_type;

        $board_select_query = Board::when($this->user_id != '', function ($query) {
              $query->addSelect(['is_like' => function($query){
                $query->select(DB::raw('count(*) as cnt'))->from('board_likes')
                  ->whereColumn('boards_id','boards.id')
                  ->where('users_id',$this->user_id)
                  ->limit(1);
              }]);
              return $query;
            })
            ->when($type == "follow",function($query){//팔로우 리스
              $query->leftJoin('follows','follows.artist_id','=','boards.artists_id' )
              ->where('follows.user_id',$this->user_id)
              ->get();
            })
            ->when($type == "select",function($query){// 아티스트 별 리스트
              $query->where('artists_id',$this->artist_id)
              ->get();
            })
            ->when($sns_type != "all",function($query){// sns별 리스트
              $query->where('type',$this->sns_type)
              ->get();
            })
            ->where('state', 1)
            ->where(function ($query) {
                $query->where('post_type','!=','image')->orWhereNotIn('type', ['vlive']);
            })
            ->where('data','!=','[]')
            ->orderby('recorded_at', 'desc')
            ->Paginate($page_count, ['*'], 'next_page', $next_token);

        if (!($board_select_query->hasMorePages())) {
            $next_page = -1;
        } else {
            $next_page = $next_token + 1;
        }

        $this->config = app('config')['celeb'][$app];
        $response['cdn_url'] = $this->config['cdn'];
        $response['next_page'] = $next_page;
        $response['body'] = $board_select_query->items();

        // Redis Cache
//        $this->redis = app('redis');
//        $this->redis->set("{$app}:Lobby:V2:page:{$next_token}", json_encode($response));
//        $this->redis->expire("{$app}:Lobby:V2:page:{$next_token}", 3600);

        return $response;
    }

    public function makeCelebSinglePage($app, $type, $params, $user = null, $view_ids = [], $inspection = null)
    {
        $total_item = 20;
        $this->cache = app('cache');

        $boards = Board::where('type', $type)
            ->where('app', $app)
            ->when($inspection != true, function ($query) {
                return $query->where('state', 1);
            })
            ->when($inspection, function ($query) {
                return $query->where('app_review', 1);
            })
            ->when(count($view_ids) > 0, function ($query) use ($view_ids) {
                return $query->whereNotIn('id', $view_ids);
            })
            ->where('post','NOT LIKE','%.%')
//            ->where('post_type','!=','image')
            ->OrWhere('type','=','news')
            ->where('data','!=','[]')
            ->orderBy('created_at', 'desc')
            ->Paginate($total_item, ['*'], 'next_page');

        // set next_page
        if (!$boards->hasMorePages()) {
            $page['next_page'] = -1;
        } else {
            if ($params['next_page']) {
                $page['next_page'] = $params['next_page'] + 1;
            } else {
                $page['next_page'] = 2;
            }
        }

        // set lobby
        $page['body'] = $boards->items();
        return $page;
    }

    //공지 파싱
    public function notice_parsing($notices, $user = null)
    {
        $parsing_results = [];
        foreach ($notices as $notice) {
            $result = new \stdClass();
            $result->notice_id = $notice->id;
            $result->thumbnail_url = $notice->thumbnail_url;
            $result->title = $notice->title;
            $result->contents = $notice->contents;
            $result->data = ($notice->data != null) ? json_decode($notice->data) : [];
            $result->created_at_string = Carbon::createFromTimeString($notice->created_at)
                ->diffForHumans();
            $parsing_results[] = $result;

        }
        return $parsing_results;
    }

    //게시물 파싱
    public function board_parsing($boards, $user = null)
    {
        $parsing_results = [];
        foreach ($boards as $board) {

            if (is_object($board)) {
                $tempBoard = $board;
                $board = $board->toArray();
                $board['comment_count'] = $tempBoard->comments->count();
            }


            if ($user != null && UserResponseToBoard::where('app', $board['app'])->where('board_id',
                    $board['id'])->where('user_id', $user->id)->where('response', 1)->count() != 0) {
                $board['is_like'] = 1;
            } else {
                $board['is_like'] = 0;
            }

            $timestamp_string = $this->DateToRelative($board['type'], $board['created_at']);

            $result = new \stdClass();
            $result->id = $board['id'];
            $result->app = $board['app'];
            $result->type = $board['type'];
            $result->post = $board['post'];
            $result->post_type = $board['post_type'];
            $result->thumbnail_url = $board['thumbnail_url'];
            $result->thumbnail_w = $board['thumbnail_w'];
            $result->thumbnail_h = $board['thumbnail_h'];
            $result->title = $board['title'];
            $result->contents = trim($board['contents']);
            $result->sns_account = $board['sns_account'];
            $result->ori_tag = $board['ori_tag'];
            $result->custom_tag = $board['custom_tag'];
            $result->data = $board['data'];
            $result->ori_thumbnail = $board['ori_thumbnail'];
            $result->ori_data = $board['ori_data'];
            $result->gender = $board['gender'];
            $result->state = $board['state'];
            $result->text_check = $board['text_check'];
            $result->search_type = $board['search_type'];
            $result->search = $board['search'];
            $result->app_review = $board['app_review'];
            $result->deleted_at = $board['deleted_at'];
            $result->created_at = $timestamp_string;
            $result->updated_at = $board['updated_at'];
            $result->face_check = $board['face_check'];
            $result->recorded_at = $board['recorded_at'];
            $result->video_duration = $this->duration_convert($board['video_duration']);
            $result->like = $board['is_like'];
            $result->item_count = $board['item_count'];
            $result->comment_count = $board['comment_count'];
            $parsing_results[] = $result;
        }
        return $parsing_results;
    }

    //게시물 파싱
    public function list_parsing($boards, $user = null)
    {
        $parsing_results = [];
        foreach ($boards as $board) {

            if (is_object($board)) {
                $tempBoard = $board;
                $board = $board->toArray();
                $board['comment_count'] = $tempBoard->comments->count();
            }

            $timestamp_string = $this->DateToRelative($board['type'], $board['created_at']);

            $result = new \stdClass();
            $result->id = $board['id'];
            $result->artist_id = $board['artists_id'];
            $result->app = $board['app'];
            $result->type = $board['type'];
            $result->post = $board['post'];
            $result->post_type = $board['post_type'];
            $result->thumbnail_url = $board['thumbnail_url'];
            $result->thumbnail_w = $board['thumbnail_w'];
            $result->thumbnail_h = $board['thumbnail_h'];
            $result->title = $board['title'];
            $result->contents = trim($board['contents']);
            $result->sns_account = $board['sns_account'];
            $result->ori_tag = $board['ori_tag'];
            $result->custom_tag = $board['custom_tag'];
            $result->data = $board['data'];
            $result->ori_thumbnail = $board['ori_thumbnail'];
            $result->ori_data = $board['ori_data'];
            $result->gender = $board['gender'];
            $result->state = $board['state'];
            $result->text_check = $board['text_check'];
            $result->search_type = $board['search_type'];
            $result->search = $board['search'];
            $result->app_review = $board['app_review'];
            $result->deleted_at = $board['deleted_at'];
            $result->created_at = $timestamp_string;
            $result->updated_at = $board['updated_at'];
            $result->face_check = $board['face_check'];
            $result->recorded_at = $board['recorded_at'];
            $result->video_duration = $this->duration_convert($board['video_duration']);
            if(isset($board['is_like'])){
                $result->like = $board['is_like'];
            }
            $result->item_count = $board['item_count'];
            $result->comment_count = $board['comment_count'];
            $parsing_results[] = $result;
        }
        return $parsing_results;
    }



    //앨범 리스트 파싱
    public function album_parsing($albums, $app, $user = null)
    {
        $result = [];
        $this->redis = app('redis');
        foreach ($albums as $album) {
            $data = new \stdClass();
            $data->album_id = $album->id;
            $data->thumbnail_url = $album->thumbnail_url;
            $data->title = $album->title;
            $data->genre = isset($album->genre) ? $album->genre : '';
            $data->released_at = isset($album->released_at) ? Carbon::createFromTimeString($album->released_at)->toDateString() : '';
            if ($album->artists->count() == 1) {
                $artist_names = $album->artists[0]->name;
            } else {
                $artist_names = '';
                foreach ($album->artists as $artist) {
                    $artist_names .= $artist->name . ',';
                }
            }
            $data->artists_name = $artist_names;
            $result[] = $data;
        }
        return $result;
    }

    //음악 리스트 파싱
    public function music_parsing($musics, $app, $user = null)
    {
        $result = [];
        $this->redis = app('redis');
        foreach ($musics as $music) {
            $data = new \stdClass();
            $music_info = new \stdClass();
            $data->music_id = $music->id;
            $data->thumbnail_url = $music->thumbnail_url;
            $data->title = $music->title;
            $data->melon_url = "intent://play?cid={$music->melon_url}&ctype=1&openplayer=Y&launchedby=kakao&ref=kakao&contsid={$music->melon_url}#Intent;scheme=melonapp;package=com.iloen.melon;end";
            $music_info->lyrics = $music->lyrics;
            if ($music->mv_url != "") {
                $music_video_board = Board::where('post', $music->mv_url)
                    ->get()->last();

                if ($music_video_board != null) {
                    $mv_board = $this->board_parsing([$music_video_board]);
                    $music_info->mv_board = $mv_board;
                } else {
                    $music_info->mv_board = null;
                }
            } else {
                $music_info->mv_board = null;
            }
            $music_info->reward_count = $music->reward_count;
            $music_info->play_count = $music->play_count;
            $data->music_info = $music_info;
            $result[] = $data;
        }
        return $result;
    }

    public function music_parsing_v2($musics, $app, $user = null)
    {
        $result = [];
        $this->redis = app('redis');
        foreach ($musics as $music) {
            $data = new \stdClass();
            $music_info = new \stdClass();
            $data->music_id = $music->id;
            $data->thumbnail_url = $music->thumbnail_url;
            $data->title = $music->title;
            $data->melon_url = "intent://play?cid={$music->melon_url}&ctype=1&openplayer=Y&launchedby=kakao&ref=kakao&contsid={$music->melon_url}#Intent;scheme=melonapp;package=com.iloen.melon;end";
            $music_info->lyrics = $music->lyrics;
            //뮤직비디오 url 있으면 첨부
            if ($music->mv_url != "") {
                $music_video_board = Board::where('post', $music->mv_url)
                    ->get()
                    ->last();
                if ($music_video_board != null) {
                    $mv_board = $this->board_parsing([$music_video_board])[0];
                    $music_info->mv_board = $mv_board;
                } else {//게시물중에 해당 url 이 없는경우
                    $music_info->mv_board = null;
                }
            } else {
                $music_info->mv_board = null;
            }
            $music_info->reward_count = $music->reward_count;
            $music_info->play_count = $music->play_count;
            $data->music_info = $music_info;
            $data->album_title = $music->album->title;

            $user_rewarded = false; // 참여한 보상인가

            //보상대기기간 확인
            if ($user != null) {

                //참여 했던 충전소 인지 Redis로 선 확인
                $redis_reward = $this->redis->hmget("{$app}:MusicReward:{$user->id}", $music->id);
                if ($redis_reward[0]) {
                    if ($music->repeat > 0) {
                        // 반복형 => 60분 지났는지 확인 안지났으면 이미 참여
                        if ($redis_reward[0] > Carbon::now()->addMinutes(($music->repeat * -1))->timestamp) {
                            // 이미 참여한 충전소
                            $user_rewarded = true;
                        }
                    } else {
                        //비반복형 키있으면 참여함 => 이미 참여한 충전소
                        $user_rewarded = true;
                    }
                } else {
                    //redis에 없어도 es로 한번 더 확인
                    $this->es = ClientBuilder::create()
                        ->setHosts([
                            [
                                'host' => env('ES_HOST'),
                                'port' => env('ES_PORT'),
                                'schema' => env('ES_SCHEMA'),
                                'user' => env('ES_USER'),
                                'pass' => env('ES_PASSWORD'),
                            ]
                        ])
                        ->setSSLVerification(false)
                        ->setRetries(3)
                        ->build();

                    // 참여 했던 충전소 인지 확인
                    if ($music->repeat > 0) {
                        // 반복형
                        $es_query['bool'] = [
                            'must' => [
                                ['term' => ['app' => $app]],
                                ['term' => ['user_id' => $user->id]],
                                ['term' => ['music_id' => $music->id]],
                                [
                                    'range' => [
                                        'reg_date' => [
                                            'gte' => Carbon::now()->addMinutes(($music->repeat * -1))->timestamp
                                        ]
                                    ]
                                ]
                            ]
                        ];
                    } else {
                        // 일반형
                        $es_query['bool'] = [
                            'must' => [
                                ['term' => ['app' => $app]],
                                ['term' => ['user_id' => $user->id]],
                                ['term' => ['music_id' => $music->id]]
                            ]
                        ];
                    }

                    // get es log
                    $es_result = $this->es->search([
                        'index' => "{$app}_history_music_reward",
                        'type' => 'docs',
                        'body' => [
                            'size' => 1,
                            'query' => $es_query
                        ]
                    ]);
                    if ($es_result['hits']['total'] > 0) {
                        // 이미 참여한 충전소
                        $user_rewarded = true;
                        // Redis
                        $this->redis->hmset("{$app}:MusicReward:{$user->id}", [
                            $music->id => $es_result['hits']['hits'][0]['_source']['reg_date']
                        ]);
                    }
                }
            }

//            $data->reward_state = $user_rewarded ? false : true;
            $data->reward_state = $user_rewarded ? 0 : 1;

            $result[] = $data;
        }
        return $result;
    }

    public function music_parsing_v3($musics, $app, $user = null)
    {
        $result = [];
        $this->redis = app('redis');
        foreach ($musics as $music) {
            $data = new \stdClass();
            $music_info = new \stdClass();
            $data->music_id = $music->id;
            $data->thumbnail_url = $music->thumbnail_url;
            $data->title = $music->title;
            $data->melon_url = "intent://play?cid={$music->melon_url}&ctype=1&openplayer=Y&launchedby=kakao&ref=kakao&contsid={$music->melon_url}#Intent;scheme=melonapp;package=com.iloen.melon;end";
            $music_info->lyrics = $music->lyrics;
            //뮤직비디오 url 있으면 첨부
            if ($music->mv_url != "") {
                $music_video_board = Board::select('id', 'post')
                    ->where('post', $music->mv_url)
                    ->get()->last();
                $music_info->mv_board_id = ($music_video_board != null) ? $music_video_board->id : 0;
                $music_info->mv_board_post = ($music_video_board != null) ? $music_video_board->post : "";
            } else {
                $music_info->mv_board_id = -1;
                $music_info->mv_board_post = "";
            }

            $music_info->reward_count = $music->reward_count;
            $music_info->play_count = $music->play_count;
            $data->music_info = $music_info;
            $data->album_title = $music->album->title;

            $user_rewarded = false; // 참여한 보상인가
            //보상대기기간 확인
            if ($user != null) {
                $chk = UserScoreLog::where('user_id', '=', $user->id)->where('music_id', $music->id)
                    ->where('created_at', '>=', Carbon::now()->addHour('-1')->format('Y-m-d H:i:s'))->get()->count();
                if (!empty($chk)) {
                    $user_rewarded = true;
                }
            }

            $data->reward_state = $user_rewarded ? 0 : 1;

            $result[] = $data;
        }
        return $result;
    }


    // 뮤직 비디오 리스트 parsing
    public function music_vidoe_board_parsing($boards, $user = null)
    {
        $parsing_results = [];
        foreach ($boards as $board) {
//            $timestamp_string = $this->DateToRelative($board['type'],$board['created_at']);
            $result = new \stdClass();
            $result->id = (int)$board['board_id'];
//            $result->app = (string) $board['app'];
//            $result->type = (string)$board['type'];
            $result->post = (string)$board['post'];
//            $result->post_type =(string)$board['post_type'];
            $result->thumbnail_url = (string)$board['thumbnail_url'];
//            $result->thumbnail_w = (int)$board['thumbnail_w'];
//            $result->thumbnail_h = (int)$board['thumbnail_h'];
            $result->title = $board['title'];
//            $result->contents = $board['contents'];
//            $result->sns_account = $board['sns_account'];
//            $result->ori_tag = json_decode($board['ori_tag']);
//            $result->custom_tag = json_decode($board['custom_tag']);
//            $result->data = json_decode($board['data']);
//            $result->ori_thumbnail = $board['ori_thumbnail'];
//            $result->ori_data = json_decode($board['ori_data']);
//            $result->gender = $board['gender'];
//            $result->state = $board['state'];
//            $result->text_check = $board['text_check'];
//            $result->search_type = $board['search_type'];
//            $result->search = $board['search'] ;
//            $result->app_review = $board['app_review'];
//            $result->deleted_at = $board['deleted_at'];
//            $result->created_at = $timestamp_string;
//            $result->updated_at = $board['updated_at'];
//            $result->face_check = $board['face_check'];
//            $result->recorded_at = $board['recorded_at'];
            /*if ($board->artists->count() == 1) {
                $artist_names = $board->artists[0]->name;
            } else {
                $artist_names = '';
                foreach ($board->artists as $artist) {
                    $artist_names .= $artist->name . ',';
                }
            }*/
            //$result->artist_name = $artist_names;
            $result->artist_name = $board->artist_name;
            $result->video_duration = $this->duration_convert($board['video_duration']);
            $parsing_results[] = $result;
        }
        return $parsing_results;
    }

    //등록시간 timestamp 안보내고 서버에서 변환후 보내도록 수정
    function comment_parsing_v3($comments, $params, $user)
    {
        $parsing_results = [];
        foreach ($comments as $comment) {
            $result = new \stdClass();
            $result->id = $comment->id;
            $result->user_id = (string)$comment->user_id;
            $result->board_id = $comment->board_id;
            $result->comment = $comment->comment;
            $result->parent_id = $comment->parent_id;
            $result->deleted_at = $comment->deleted_at;
            $create_at_humans_string = (new Carbon($comment->created_at))->diffForHumans();
            $result->created_at = $create_at_humans_string;
            $result->update_at = $comment->updated_at;
            if ($comment->parent_id == null || isset($params['board_id'])) { //일반댓글
                $result->comment_type = ($comment->type == 'N') ? 0 : (($comment->type == 'C') ? 1 : 4);  // N => 0 , C => 1,  ( B => 4 베댓 이었는대 베댓 기획 폐기 안씀)
            } else { //대댓글
                $result->comment_type = ($comment->type == 'N') ? 2 : (($comment->type == 'C') ? 3 : 5);  // N => 2 , C => 3,  ( B => 5 베대댓 이었는대 베대댓 기획 폐기 안씀)
            }
            $result->re_comments_count = $comment->replies()->count();

            if ($user != null) {
                $check = UserResponseToComment::where('comment_id', $comment->id)
                    ->where('user_id', $user->id)
                    ->where('response', 1)
                    ->count();
                if ($check > 0) {
                    $result->is_like = true;
                } else {
                    $result->is_like = false;
                }
                $report_check = UserResponseToComment::where('comment_id', $comment->id)
                    ->where('response', 0)
                    ->where('user_id', $user->id)
                    ->count();
                if ($report_check > 0) {
                    $result->is_report = true;
                } else {
                    $result->is_report = false;
                }
            } else {
                $result->is_like = false;
                $result->is_report = false;
            }
            $result->like_count = $comment->like_count;
            $result->report_count = $comment->report_count;
            $result->user_nickname = $comment->user_nickname;

            $result->user_profile_photo_url = $comment->user_profile_photo_url;
            $parsing_results[] = $result;
        }

        return $parsing_results;
    }

    //댓글마다 유저 등급 붙이던 로직 제거
    function comment_parsing_v2($comments, $params, $user)
    {
        foreach ($comments as $comment) {
            $comment->user_id = (string)$comment->user_id;
            $comment->comment_type = ($comment->type == 'N') ? 0 : (($comment->type == 'C') ? 1 : 2);
            $comment->re_comments_count = $comment->replies()->count();
            $comment->created_at_timestamp = (string)($comment->created_at->timestamp);
            $comment->updated_at_timestamp = (string)($comment->updated_at->timestamp);

            if ($user != null) {
                $check = UserResponseToComment::where('comment_id', $comment->id)
                    ->where('user_id', $user->id)
                    ->where('response', 1)
                    ->count();
                if ($check > 0) {
                    $comment->is_like = true;
                } else {
                    $comment->is_like = false;
                }
                $report_check = UserResponseToComment::where('comment_id', $comment->id)
                    ->where('response', 0)
                    ->where('user_id', $user->id)
                    ->count();
                if ($report_check > 0) {
                    $comment->is_report = true;
                } else {
                    $comment->is_report = false;
                }
            } else {
                $comment->is_like = false;
                $comment->is_report = false;
            }
        }

        return $comments;
    }

    function schedule_parsing($schedules, $user = null, $timezone = 'Asia/Seoul')
    {
        $parsing_results = [];

        foreach ($schedules as $schedule) {
            $result = new \stdClass();
            $result->date = $schedule->date;
            $result->time = Carbon::createFromTimeString($schedule->scheduled_at,
                'UTC')->setTimezone($timezone)->format('H:i');
            $result->title = $schedule->title;
            $result->contents = $schedule->contents;

            $parsing_results[] = $result;
        }
        return $parsing_results;
    }

    // 각 크롤링사이트 시간대에 맞춰서 조절해서 relative string 으로  변환
    // 대부분 사이트 utc 기준 트위터만 시간대가 +18시간임
    // todo 추후 해외서비스시 $request -> ip 확인후 해당ip timezone 에 맞춰서 추가 ifelse 분류 필요
    protected function DateToRelative($type, $date_string)
    {
//        Carbon::setLocale('ko'); // todo? 영어 아니라 해당국가 언어에 맞춰서 표시할경우 ifelse 분류 필요

        if ($type == 'twitter') {
            $date = Carbon::createFromTimeString($date_string)->addHours(6);
        } elseif ($type == 'instagram') {
            $date = Carbon::createFromTimeString($date_string)->addhours(-9);
        } elseif ($type == 'fanfeed') {

            $date = Carbon::createFromTimeString($date_string);
        } else {
            $date = Carbon::createFromTimeString($date_string)->addhours(-9);
        }
        $result = $date->diffForHumans();
        return $result;
    }

    //동영상길이 한시간 이하 일경우 분 초만 표시
    protected function duration_convert($duration_string = null)
    {

        if ($duration_string == null) {
            return '';
        }

        if( substr($duration_string,0,2) !== '00')
        {
            return $duration_string;
        }

        return substr($duration_string,3,5) ;
    }

    //아티스트 리스
    public function makeArtistList($app, $next_token = 1, $type, $user_id, $search_query)
    {
        // Redis Connection
        if ($next_token == 0) {
            $next_token = 1;
        }
        $this->type = $type;
        $this->user_id = $user_id;
        $this->search_query = $search_query;
        $page_count = 20;

        $board_select_query = Artist::Select(DB::raw("* ,0 as is_added"))
            ->when($this->user_id != '', function ($query) {
              $query->addSelect(['is_follow' => function($query){
                $query->select(DB::raw('count(*) as cnt'))->from('follows')
                  ->whereColumn('artist_id','artists.id')
                  ->where('user_id',$this->user_id)
                  ->limit(1);
              }]);
              return $query;
            })
            ->where('app', $app)
            ->when(($type != 'all'), function ($query) {
                $type_arr = explode("," ,$this->type);
                return $query->whereIn('team_type', $type_arr);
            })
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search_query.'%')->orWhere('name_eng', 'like', '%'.$this->search_query.'%');
            })
            ->orderby('name', 'asc')

            ->Paginate($page_count, ['*'], 'next_page', $next_token);

        if (!($board_select_query->hasMorePages())) {
            $next_page = -1;
        } else {
            $next_page = $next_token + 1;
        }
        $follow_cnt = Follow::Select(DB::raw("count(*) as cnt"))
        ->where("user_id",$this->user_id)
        ->get();


        $this->config = app('config')['celeb'][$app];
        $response['follow_cnt'] = $follow_cnt[0]->cnt;
        $response['cdn_url'] = $this->config['cdn'];
        $response['next_page'] = $next_page;
        $response['body'] = $board_select_query->items();

        // Redis Cache
//        $this->redis = app('redis');
//        $this->redis->set("{$app}:Lobby:V2:page:{$next_token}", json_encode($response));
//        $this->redis->expire("{$app}:Lobby:V2:page:{$next_token}", 3600);

        return $response;
    }

    //아티스트 팔로우 리스트
    public function makeArtistListFollow($user_id)
    {
        $this->user_id = $user_id;

        $board_select_query = Artist::Join('follows','follows.artist_id','=','artists.id')
            ->where('follows.user_id','=',$user_id)
            ->orderby('artists.created_at', 'desc')
            ->get();

        $this->config = app('config')['celeb']['fantaholic'];
        $response['cdn_url'] = $this->config['cdn'];

        $response['body'] = $board_select_query;

        return $response;
    }



}
