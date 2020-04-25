<?php

namespace App\Http\Controllers\Api\Search;

use App\Album;
use App\Artist;
use App\Board;
use App\Http\Controllers\Controller;
use App\Keyword;
use App\Music;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    protected $response;

    public function __construct()
    {
        $this->response = new \App\Lib\Response();
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $params['schKeyword'] = $request->get('schKeyword', '');
        Log::debug(__METHOD__ . ' - params - ' . json_encode($params));

        $boardList = []; //Artist Feed
        $artistList = []; //Artist Music
        $fanFeedList = []; //Fan Feed

        $boardList = Board::whereNotIn('type', ['fanfeed'])
            ->where(function ($q) use ($params) {
                return $q->whereLike(['title', 'contents'], $params['schKeyword']);
            })
            ->where('state', 1)
            ->where('post','NOT LIKE','%.%')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()->map(function ($board) {
                return [
                    'id' => $board->id,
                    'type' => $board->type,
                    'post' => $board->post,
                    'title' => $board->title,
                    'contents' => $board->contents,
                    'thumbnail_url' => $board->thumbnail_url,
                    'ori_thumbnail' => $board->ori_thumbnail,
                    'created_at' => $board->created_at->diffForHumans()
                ];
            })->toArray();

        /*$fanFeedList = Board::whereIn('type', ['fanfeed'])
            ->where(function ($q) use ($params) {
                return $q->whereLike(['title', 'contents'], $params['schKeyword']);
            })
            ->where('state', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)->get()->map(function ($board) {
                return [
                    'id' => $board->id,
                    'type' => $board->type,
                    'post' => $board->post,
                    'title' => $board->title,
                    'contents' => $board->contents,
                    'thumbnail_url' => $board->thumbnail_url,
                    'created_at' => $board->created_at->diffForHumans()
                ];
            })->toArray();


        $musics = Music::whereLike(['title', 'lyrics'], $params['schKeyword'])
            ->where('app', config('celeb.krieshachu.app_name'))->get()
            ->map(function ($music) {
                return [
                    'id' => $music->id,
                    'type' => 'music',
                    'post' => null,
                    'title' => $music->title,
                    'contents' => "",
                    'thumbnail_url' => $music->thumbnail_url,
                    'created_at' => $music->created_at->diffForHumans()
                ];
            })->toArray();

        if (count($musics) > 0) {
            foreach ($musics as $key => $val) {
                array_push($artistList, $val);
            }
        }


        $mvs = DB::table('boards')
            ->join('musics', 'musics.mv_url', '=', 'boards.post')
            ->where(function ($query) use ($params) {
                return $query->orWhere('boards.title', 'like', '%' . $params['schKeyword'] . '%')
                    ->orWhere('boards.contents', 'like', '%' . $params['schKeyword'] . '%');
            })
            ->where('boards.app', config('celeb.krieshachu.app_name'))
            ->whereNotNull('musics.mv_url')
            ->select('boards.*')
            ->whereNull('boards.deleted_at')->get()->map(function ($mv) {
                return [
                    'id' => $mv->id,
                    'type' => 'mv',
                    'post' => $mv->post,
                    'title' => $mv->title,
                    'contents' => "",
                    'thumbnail_url' => $mv->thumbnail_url,
                    'created_at' => Carbon::parse($mv->created_at)->diffForHumans()
                ];
            })->toArray();;

        if (count($mvs) > 0) {
            foreach ($mvs as $key => $val) {
                array_push($artistList, $val);
            }
        }

        $albums = Album::whereLike(['title'], $params['schKeyword'])
            ->where('app', config('celeb.krieshachu.app_name'))->get()
            ->map(function ($album) {
                return [
                    'id' => $album->id,
                    'type' => 'album',
                    'post' => null,
                    'title' => $album->title,
                    'contents' => "",
                    'thumbnail_url' => $album->thumbnail_url,
                    'created_at' => $album->created_at->diffForHumans()
                ];
            });

        if (count($albums) > 0) {
            foreach ($albums as $key => $val) {
                array_push($artistList, $val);
            }
        }*/

        $result['more']['board_more'] = (4 < count($boardList)) ? true : false;
        //$result['more']['fan_feed_more'] = (4 < count($fanFeedList)) ? true : false;
        //$result['more']['music_more'] = (4 < count($artistList)) ? true : false;

        $result['board_list'] = array_slice($boardList, 0, 4);
        //$result['fan_feed_list'] = array_slice($fanFeedList, 0, 4);
        //$result['music_list'] = array_slice($artistList, 0, 4);

        return Response::json(($this->response->set_response(0, $result)), 200);
    }

    public function show(Request $request, $type = 'board')
    {
        $params = $request->all();
        $params['schKeyword'] = $request->get('schKeyword', '');
        $params['schType'] = $type;
        Log::debug(__METHOD__ . ' - params - ' . json_encode($params));

        $pageInfo['current_page'] = 1;
        $pageInfo['last_page'] = 1;

        $result = [];
        if ($params['schType'] === "board") {
            $result = Board::whereNotIn('type', ['fanfeed'])
                ->whereLike(['title', 'contents'], $params['schKeyword'])
                ->where('post','NOT LIKE','%.%')
                ->where('state', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $pageInfo['current_page'] = $result->currentPage();
            $pageInfo['last_page'] = $result->lastPage();

            $result = $result->map(function ($board) {
                return [
                    'id' => $board->id,
                    'type' => $board->type,
                    'post' => $board->post,
                    'title' => $board->title,
                    'contents' => $board->contents,
                    'thumbnail_url' => $board->thumbnail_url,
                    'ori_thumbnail' => $board->ori_thumbnail,
                    'created_at' => $board->created_at->diffForHumans()
                ];
            })->toArray();
        }

        /*if ($params['schType'] === "fan_feed") {
            $result = Board::whereIn('type', ['fanfeed'])
                ->whereLike(['title', 'contents'], $params['schKeyword'])
                ->where('state', 1)
                ->orderBy('created_at', 'desc')->paginate(10);
            $pageInfo['current_page'] = $result->currentPage();
            $pageInfo['last_page'] = $result->lastPage();
            $result = $result->map(function ($board) {
                return [
                    'id' => $board->id,
                    'type' => $board->type,
                    'post' => $board->post,
                    'title' => $board->title,
                    'contents' => $board->contents,
                    'thumbnail_url' => $board->thumbnail_url,
                    'created_at' => $board->created_at->diffForHumans()
                ];
            })->toArray();
        }

        if ($params['schType'] === "music") {
            $musics = Music::whereLike(['title', 'lyrics'], $params['schKeyword'])
                ->where('app', config('celeb.krieshachu.app_name'))->get()
                ->map(function ($music) {
                    return [
                        'id' => $music->id,
                        'type' => 'music',
                        'post' => null,
                        'title' => $music->title,
                        'contents' => "",
                        'thumbnail_url' => $music->thumbnail_url,
                        'created_at' => $music->created_at->diffForHumans()
                    ];
                })->toArray();

            if (count($musics) > 0) {
                foreach ($musics as $key => $val) {
                    array_push($result, $val);
                }
            }

            $mvs = DB::table('boards')
                ->join('musics', 'musics.mv_url', '=', 'boards.post')
                ->where(function ($query) use ($params) {
                    return $query->orWhere('boards.title', 'like', '%' . $params['schKeyword'] . '%')
                        ->orWhere('boards.contents', 'like', '%' . $params['schKeyword'] . '%');
                })
                ->where('boards.app', config('celeb.krieshachu.app_name'))
                ->whereNotNull('musics.mv_url')
                ->select('boards.*')
                ->whereNull('boards.deleted_at')->get()->map(function ($mv) {
                    return [
                        'id' => $mv->id,
                        'type' => 'mv',
                        'post' => $mv->post,
                        'title' => $mv->title,
                        'contents' => "",
                        'thumbnail_url' => $mv->thumbnail_url,
                        'created_at' => Carbon::parse($mv->created_at)->diffForHumans()
                    ];
                })->toArray();;


            if (count($mvs) > 0) {
                foreach ($mvs as $key => $val) {
                    array_push($result, $val);
                }
            }

            $albums = Album::whereLike(['title'], $params['schKeyword'])
                ->where('app', config('celeb.krieshachu.app_name'))->get()
                ->map(function ($album) {
                    return [
                        'id' => $album->id,
                        'type' => 'album',
                        'post' => null,
                        'title' => $album->title,
                        'contents' => "",
                        'thumbnail_url' => $album->thumbnail_url,
                        'created_at' => $album->created_at->diffForHumans()
                    ];
                });

            if (count($albums) > 0) {
                foreach ($albums as $key => $val) {
                    array_push($result, $val);
                }
            }
        }*/


        $result = array_merge(['data' => $result], $pageInfo);
//        {{ $result->appends($params)->links(); }}

        return Response::json(($this->response->set_response(0, $result)), 200);
    }
}
