<?php

namespace App\Http\Controllers\Admin\Data;

use App\Board;
use App\CollectBatch;
use App\Http\Controllers\Controller as BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\ImageSource;

use App\BatchPool;
use App\Azure\Batch\Batch;

class BatchController extends BaseController
{
    protected $batch;

    public function __construct()
    {
        $this->batch = Batch::create()
            ->setUrl(config('azure.batch.url'))
            ->setKey(config('azure.batch.key'))
            ->setAccount(config('azure.batch.account'))
            ->setApiVersion(config('azure.batch.api_version'));
////        dump('구글 사진 검사 api 추가 테스트');
//        $boards = Board::orderBy('id','desc')->limit(1)->get();
//
//        //2차 최적화 api 한번에 이미지 10개 제한하기
//        $board_count = count($boards);
//        $feature =  new Feature();
//        $feature->setType(6);                     //1 => face_check , 5 => text_check
//
//        for ($k=0,$i=0 ; $k < $board_count; $k++){
//            $path = env('CDN_URL').$boards[$k]->thumbnail_url;
//            $imagesoure = new ImageSource();
//            $imagesoure->setImageUri($path);
//            $image = new Image();
//            $image->setSource($imagesoure);
//            $request = new AnnotateImageRequest();
//            $request->setImage($image);
//            $request->setFeatures([$feature]);
//            $requests[] = $request;
//            // 10개미만일경우 + 이미지 10개마다 요청
//            if(($k+1 == $board_count && $board_count <10) ||($k%10 == 9) || ($k+1 == $board_count && $board_count > 10)){
//                $imageannotator_client = new ImageAnnotatorClient();
//                $responses = $imageannotator_client->batchAnnotateImages($requests)->getResponses();
////                dump($responses);
////                dump($safe = $responses[0]->getSafeSearchAnnotation());
////                dump($safe->getAdult());
//                for($i;$i<=$k;$i++){
//                    $text_count = count($responses[$i%10]->getTextAnnotations());
//                    if($text_count>0){
//                        $text_exist[] = $boards[$i]->id;
//                    }else{
//                        $text_not[] = $boards[$i]->id;
//                    }
//                }
//                $imageannotator_client->close();
//                $requests = [];
//            }
//            $logs[]=[
//                'board_id'      =>  $boards[$k]->id,
//                'board_type'    =>  $boards[$k]->type,
//                'update_name'   =>  'text_check',
//                'created_at'    =>  Carbon::now()->toDateTimeString(),
//                'updated_at'    =>  Carbon::now()->toDateTimeString()
//            ];
//        }
//        dd('구글 사진 검사 api 추가 테스트 end');
//
//        dump('tst');
//        foreach($this->batch->listJobschedule() as $schedule){
//            $account[] = substr($schedule->id,5);
//        }
//        dump($account);
//        $need_list = CollectBatch::wherenotin('search',$account)
//            ->where('board','instagram')
//            ->where('type','account')
//            ->where('state',1)
//            ->get();
//        foreach($need_list as $value){
//            $need_account[] = str_replace('.','',$value->search);
//        }
//        dump($need_account);
//        foreach($need_account as $need){
//            if(!in_array($need,$account)){
//                $real_need[]=$need;
//            }
//        }
//        dd($real_need);
//
//
//
//        $job_schedule_list=$this->batch->listJobschedule();
//        $job_list= $this->batch->listjob();
////        dd($job_list);
////        foreach($job_schedule_list as $schdule){
////            $job_list=$this->batch->listJobFromSchedule($schdule->id);
//            foreach($job_list as $job){
////                dump($job);
//                dump($this->batch->listTask($job->id));
//                dump($this->batch->listTask($job->id)[0]->executionInfo);
////                $is_complete = $this->batch->isCompletedJob($job->id);
////                dump($is_complete);
//            }
////        }
    }

    /**
     * 배치 관리 - 풀 관리
     */
    public function index(Request $request)
    {
        $rows = BatchPool::paginate(15);

        $pools = [];
        foreach ($rows as $row) {
            $pool = $this->batch->getPool($row->pool_id);
            $pools[$row->pool_id] = $pool;
        }

        return view('data.pools')->with([
            'title' =>  'Azure Batch Pools',
            'azure_batch_pools_menu' => 'active',
            'rows' => $rows,
            'pools' => $pools,
        ]);
    }

    /**
     * 배치 관리 - 풀 등록
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'pool_id' => 'required|string',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::REQUEST_INVALID_DATA,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 200);
        }

        try {

            BatchPool::create([
                'pool_id' => $request->input('pool_id'),
            ]);

        } catch (QueryException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::DATABASE_EXCEPTION,
                'message' => 'Failed to create product',
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

    /**
     * 배치 관리 - Jobs
     */
    public function jobs()
    {
        $jobs = $this->batch->listJob();

        $pools = BatchPool::get();

        return view('data.jobs')->with([
            'title' => 'Azure Batch Jobs',
            'rows' => $jobs,
            'pools' => $pools,
        ]);
    }

    /**
     * 배치 관리 - Job & Task 등록
     */
    public function job_store(Request $request)
    {
        try {

            $request->validate([
                'pool_id' => 'required|string',
                'instagram_id' => 'required|string',
                'gender' => 'required|string',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::REQUEST_INVALID_DATA,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 200);
        }

        try {

            $pool = BatchPool::where('id', $request->input('pool_id'))->get()->last();

            if (!$pool) {
                return response()->json([
                    'result' => 'fail',
                    'code' => 0,
                    'message' => 'Pool not found',
                    'data' => new \stdClass(),
                ], 500);
            }

            $instagramid = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $request->input('instagram_id'));
            $job_id = "pinxyJob{$instagramid}";

            $job_add = $this->batch->addJob([
                'jobId' => $job_id,
                'poolId' => $pool->pool_id,
            ]);

            if (!$job_add) {
                return response()->json([
                    'result' => 'fail',
                    'code' => 0,
                    'message' => 'Failed to create product',
                    'data' => new \stdClass(),
                ], 500);
            } else {
                $command_line = "sudo runuser -l tdi -c 'python3 /home/tdi/crt9_insta_crawler_once/index.py {$request->input('gender')} {$request->input('instagram_id')}'";

                $task_add = $this->batch->addTask([
                    'jobId' => $job_id,
                    'taskId' => "pinxyTask{$instagramid}",
                    'commandLine' => $command_line,
                ]);

                if (!$task_add) {
                    return response()->json([
                        'result' => 'fail',
                        'code' => 0,
                        'message' => 'Failed to create product',
                        'data' => new \stdClass(),
                    ], 500);
                }
            }
        } catch (QueryException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::DATABASE_EXCEPTION,
                'message' => 'Failed to create product',
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

    /**
     * 배치 관리 - Job Schedule
     */
    public function jobschedule(Request $request)
    {
        $schedules = $this->batch->listJobschedule();
        
        //다음실행시간순으로 정렬
        usort($schedules, function ($a,$b){
            if($a->executionInfo->nextRunTime == $b->executionInfo->nextRunTime){
                return 0;
            }
            return ($a->executionInfo->nextRunTime < $b->executionInfo->nextRunTime) ? -1:1;
        });
        
        $pools = BatchPool::get();

        return view('data.jobschedule')->with([
            'title' => 'Azure Batch Jobs',
            'rows' => $schedules,
            'pools' => $pools,
        ]);
    }

    /**
     * 배치 관리 - Job Schedule 등록
     */
    public function jobschedule_store(Request $request)
    {
        try {

            $request->validate([
                'instagram_id' => 'required|string',
                'pool_id' => 'required|string',
                'gender' => 'required|string',
                'schedule_day' => 'required|integer',
                'schedule_hour' => 'required|integer',
                'schedule_min' => 'required|integer',
                'schedule_sec' => 'required|integer',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::REQUEST_INVALID_DATA,
                'message' => $e->getMessage(),
                'data' => new \stdClass(),
            ], 200);
        }

        try {

            $pool = BatchPool::where('id', $request->input('pool_id'))->get()->last();

            if (!$pool) {
                return response()->json([
                    'result' => 'fail',
                    'code' => 0,
                    'message' => 'Pool not found',
                    'data' => new \stdClass(),
                ], 500);
            }

            $instagramid = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $request->input('instagram_id'));
            $command_line = "sudo runuser -l tdi -c 'python3 /home/tdi/crt9_insta_crawler_once/reindex.py {$request->input('gender')} {$request->input('instagram_id')}'";

            $interval_d = ($request->input('schedule_day') > 0)? "{$request->input('schedule_day')}D" : '';
            $interval_h = ($request->input('schedule_hour') > 0)? "{$request->input('schedule_hour')}H" : '1H';
            $interval_m = ($request->input('schedule_min') > 0)? "{$request->input('schedule_min')}M" : '';
            $interval_s = ($request->input('schedule_sec') > 0)? "{$request->input('schedule_sec')}S" : '';
            $interval = "P{$interval_d}T{$interval_h}{$interval_m}{$interval_s}";

            $params = [
                'scheduleId' => "task_{$instagramid}",
                'jobId' => "jobRe{$instagramid}",
                'poolId' => $pool->pool_id,
                'commandLine' => $command_line,
                'interval' => $interval,
            ];

            $add = $this->batch->addJobschedule($params);

            if (!$add) {
                return response()->json([
                    'result' => 'fail',
                    'code' => 0,
                    'message' => 'Failed to create product',
                    'data' => new \stdClass(),
                ], 500);
            }

        } catch (QueryException $e) {

            return response()->json([
                'result' => 'fail',
                'code' => ErrorCodes::DATABASE_EXCEPTION,
                'message' => 'Failed to create product',
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
