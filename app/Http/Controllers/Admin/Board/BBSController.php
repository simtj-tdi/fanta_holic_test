<?php

namespace App\Http\Controllers\Admin\Board;

use App\Board;
use App\Enums\ChannelType;
use App\Tag;
use App\UpdateLog;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Lib\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;


class BBSController extends BaseController
{
    protected $logs;
    protected $util;

    public function __construct(UpdateLog $logs)
    {
        $this->util = new Util();

        $this->logs = $logs;

    }

    public function index(Request $request)
    {
        $logs = $this->logs->latest('created_at')->paginate(20, ['*'], 'log_page');
        Log::info(__METHOD__ . ' - request all - ' . json_encode($request->all()));

        $params = [
            'board_id' => $request->input('board_id', null),
            'schChannel' => $request->input('schChannel'),
            'schType' => $request->input('schType'),
            'schVal' => $request->input('schVal'),
            'schDateType' => $request->input('schDateType', 'recorded_at'),
            'startDate' => $request->input('startDate', Carbon::now()->addDays(-7)->toDateString()),
            'endDate' => $request->input('endDate', Carbon::now()->toDateString()),
            'schState' => $request->input('schState'),
            'tags' => $request->input('tags'),
            'text_check' => $request->input('text_check', 3),
            'face_check' => $request->input('face_check', 'all'),
            'app_review' => $request->input('app_review', null),
            'search' => $request->input('search')
        ];
//        print_r($params);
//        dd($params);
        if (empty($request->user()->app)) {
            Log::error(__METHOD__ . ' - user app info empty - ' . json_encode($request->user()));
//            throw new \Exception('user app 정보가 없습니다.', 400);
        }

        $params['app'] = $request->user()->app;
        Log::info(__METHOD__ . ' - params - ' . json_encode($params));
        // 영어 숫자 => true    그외 false
        $params['is_eng_or_num'] = ctype_alnum($params['tags']);

        $boardQuery = Board::getList($params);

        $total_cnt = $boardQuery->count();
        $params['pageCnt'] = 50;
//        dd($params['pageCnt']);
        $rows = $boardQuery->orderBy('created_at', 'desc')
            ->Paginate($params['pageCnt']);



        foreach ($rows as $row) {
            if ($row->state == 0 && $row->recorded_at > Carbon::now()->addHours(-25)) {
                $row->new = true;
            }
        }

        $rows->appends($params)->links();

        $params['currentPage'] = $rows->currentPage();

        //2019.2.8 cch 태그검색자동완성
        $tags = json_encode(Tag::all()->map(function ($tag) {
            return $tag->name;
        })->toArray());


        //@end 2019.2.8 cch 태그검색자동완성
        return view('Boards.index')->with([
            'total_menu' => 'active',
            'params' => $params,
            'tag_list' => $tags,
            'rows' => $rows,
            'total' => $total_cnt,
            'logs' => $logs
        ]);
    }

    public function show(Request $request, $id = null)
    {
        $params = $request->all();
        $params['info'] = null;
        if ($id !== null) {
            $params['info'] = Board::with(['comments'])->find($id);
            //$params['info']['data'] = json_decode($params['info']['data']);
            $params['type'] = $params['info']['type'];
        }

        return view('Boards.form')->with($params);
    }

    public function edit()
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {
        $reqParams = [
            'type'              => $request->input('type'),
            'title'             => $request->input('title'),
            'contents'          => $request->input('contents'),
            'state'             => $request->input('state'),
            'ori_thumbnail'     => $request->input('ori_thumbnail'),
            'app_review'        => $request->input('app_review'),
            'ori_tag'           => $request->input('ori_tag'),
            'custom_tag'        => $request->input('custom_tag'),
            'validation_at'        => $request->input('validation_at'),
        ];


        try {

            $params = $request->only($reqParams);
            $params['app'] = 'fantaholic';
            if ($request->user() != null) {
                $params['app'] = $request->user()->app;
            }


            if (empty($id)) {
                Log::error(__METHOD__ . ' - validation fail - ' . json_encode($params));
                throw new \Exception('데이터 검증에 실패하였습니다');
            }

            $board = Board::find($id);

            foreach ($params as $columnName => $columnVal) {
                if (in_array($columnName, ['ori_tag', 'custom_tag'])) {
                    if(!empty($columnVal)){
                        $columnVal = explode(',', $columnVal);
                    }

                }
                $board[$columnName] = $columnVal;
            }

            // file save
            $util = new Util();

            if ($request->hasFile('thumbnail')) {
                $path = 'images/' . $params['type'] . '/';
                $resized_image = $util->SaveThumbnailAzureFixReturnSize($request->file('thumbnail'), $path, 'pinxy',
                    $params['type']);
                $board['thumbnail_url'] = "/" . $path . $resized_image['filename'];
                $board['thumbnail_w'] = (int)$resized_image['width'];
                $board['thumbnail_h'] = (int)$resized_image['height'];
            }

            if ($request->hasFile('data_files')) {
                $files = $request->File('data_files');
                foreach ($files as $file) {
                    $file_type = $file->getMimeType();
                    if (strstr($file_type, "video/")) {
                        $uploads[] = [
                            'type' => 'video',
                            'video_path' => 'video/fanfeed/src/',
                            'poster_path' => 'video/fanfeed/poster/',
                            'file' => $file
                        ];
                        $fanfeed_data['post_type'] = 'video';
                    } else {
                        if (strstr($file_type, "image/")) {
                            $uploads[] = [
                                'type' => 'image',
                                'path' => 'images/fanfeed/src/',
                                'file' => $file
                            ];
                            $fanfeed_data['post_type'] = 'img';
                        } else {
                            return $this->response->set_response(-1001, null);
                        }
                    }
                }
                if (count($files) > 1) {
                    $fanfeed_data['post_type'] = 'post';
                }

                foreach ($uploads as $upload) {
                    if ($upload['type'] == 'video') {
                        $video_filename = $util->SaveFileAzure($upload['file'], $upload['video_path']);
                        $poster_filename = $util->SaveVideoPoster($upload['file'], $upload['poster_path'],
                            $request->user());
                        $fanfeed_data['data'][] = [
                            $upload['type'] => [
                                'src' => "/" . $upload['video_path'] . $video_filename,
                                'poster' => "/" . $upload['poster_path'] . $poster_filename,
                            ],
                        ];
                    } elseif ($upload['type'] == 'image') {
                        $filename = $util->SaveFileAzure($upload['file'], $upload['path']);
                        $fanfeed_data['data'][] = [
                            $upload['type'] => "/" . $upload['path'] . $filename
                        ];
                    }
                }

                $board['data'] = json_encode($fanfeed_data['data']);


            }
            //$board = (array) $board;



            //Board::where('id', $board['id'])->update($board);
            $result = Board::where('id', $board['id'])->update(
                ['title' => $board['title'],
                'post' => $board['post'],
                'contents' => $board['contents'],
                'thumbnail_url' => $board['thumbnail_url'],
                'thumbnail_w' => $board['thumbnail_w'],
                'thumbnail_h' => $board['thumbnail_h'],
                'ori_tag' => $board['ori_tag'],
                'custom_tag' => $board['custom_tag'],
                'ori_thumbnail' => $board['ori_thumbnail'],
                'data' => $board['data'],
                'ori_data' => $board['ori_data'],
                'state' => $board['state'],
                'recorded_at' => $board['recorded_at'],
                'video_duration' => $board['video_duration'],
                'item_count' => $board['item_count'],
                'best_list_cnt' => $board['best_list_cnt'],
                'created_at' => $board['created_at'],
                'img_count' => $board['img_count'],
                'validation_at' =>$reqParams['validation_at'],]
            );

//            return redirect(route('board.index'))->withSuccess('등록에 성공하였습니다');
        } catch (\Exception $e) {
            $e->getTrace();
            Log::error(__METHOD__ . ' - throw catch - ' . $e->getMessage());
            return redirect()->back()->with('error', '등록에 실패하였습니다.[' . $e->getMessage() . ']');
        }

        if ( $request->ajax() ) {
            return Response::json(['rst' => true], 200);
        }
        else {
            return redirect('/admin/boards?schChannel='.$params['type']);
            //return redirect(route('board.index'))->withSuccess('등록에 성공하였습니다');
        }
    }


    public function select_update(Request $request, $id) {
        $reqParams = [
            'state'             => $request->input('state'),
        ];

        $board = Board::find($id);
        $result = Board::where('id', $board['id'])->update(
            [
                'state' => $reqParams['state'],
                'validation_at' => Carbon::now()->toDateTimeString(),
            ]
        );
        return;
    }

    public function patch(Request $request)
    {
        $params = $request->json()->all();


        if (empty($params)  ) {
            Log::error(__METHOD__ . ' - validation fail - ' . json_encode($params));
            throw new \Exception('데이터 검증에 실패하였습니다');
        }

        foreach($params as $key => $info)
        {
            $board = Board::find($info['id']);
            foreach ($info as $columnName => $columnVal) {
                if (in_array($columnName, ['ori_tag', 'custom_tag'])) {
                    $columnVal = explode(',', $columnVal);
                }
                $board[$columnName] = $columnVal;
            }
            $board->save();
        }
        return Response::json(['rst' => true], 200);
    }

    public function delete(Request $request, $id)
    {

        try
        {
            Log::info(__METHOD__.' - params - '.json_encode($request->all()));

            \Validator::make($request->all(),[
                'id' => 'required|exists:boards,id'
            ]);

            $board = Board::find($id);

            $board->delete();
            if ( !$request->ajax() ) {
                return redirect()->back();
            }
            else {
                return Response::json([],200);
            }
//            Alert::success('Yep', 'Title');

        }catch(\Exception $e)
        {

        }


    }
}
