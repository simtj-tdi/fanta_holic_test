<?php

namespace App\Http\Controllers\Admin\Data;

use App\Board;
use App\CollectBatch;
use App\BatchPool;
use App\Azure\Batch\Batch;
use App\CollectRule;
use App\Http\Controllers\Controller as BaseController;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class CrawlingController extends BaseController
{
    public function index(Request $request)
    {
        $count=20;

        $params = [
            'board' =>  $request->input('board','instagram'),
            'type'  =>  $request->input('type','account'),
            'search'    =>  $request->input('tags'),
            'gender'    =>  $request->input('gender',1)
        ];

        $params['is_eng_or_num'] = ctype_alnum($params['search']);
        if($params['board']=='instagram'){
            if($params['type']!='hashtag' && $params['type'] != 'account'){
                $params['type']='account';
            }
        }
        if($params['board']=='youtube'){
            if($params['type']!='channel' && $params['type'] != 'keyword'){
                $params['type']='channel';
            }
        }

        $searchs = CollectBatch::where('type',$params['type'])
            ->where('board',$params['board'])
            ->where('gender',$params['gender'])
            ->when($params['search'],function ($query) use ($params)
            {
                if($params['is_eng_or_num'])
                {
                    //영어 숫자 일경우 3글자부터 중복체크
                    if(mb_strlen($params['search'])>2)
                    {
                        return $query->where('search', 'like', '%' . $params['search'] . '%');
                    }
                    else
                    {
                        return $query->where('search','like','%"'.$params['search'].'"%');
                    }
                }
                else
                {
                    //한글,일본어,한자 2글자부터 중복체크
                    if(mb_strlen($params['search'])>1)
                    {
                        return $query->where('search', 'like', '%' . $params['search'] . '%');
                    }
                    else
                    {
                        return $query->where('search','like','%"'.$params['search'].'"%');
                    }
                }
            })
            ->orderBy('id','desc')
            ->Paginate($count);
//        dd($searchs);
        {{ $searchs->appends($params)->links(); }}


        $today_timestamp = Carbon::today()->timestamp;
        $end_of_day_timestamp = Carbon::today()->endOfDay()->timestamp;
        $yesterday_timestamp =Carbon::yesterday()->timestamp;

        if($searchs->count()>0){
            //검색어 배열생성 통계값 초기화 선언
            foreach($searchs as $search){
                $search_words[] = $search->search;
                $search->total = 0 ;  //수집량
                $search->open_count = 0;  // 게시중인 수
                $search->today_count = 0;   //
                $search->yesterday_count = 0;
            }

            $articles = Board::where('type',$params['board'])
                ->select('created_at','state','search')
                ->whereIn('search',$search_words)
                ->get();

            foreach($articles as $article){

                $search_id = $this->search_search_word($article->search,$searchs);
                if($search_id != -1){
                    $searchs[$search_id]->total++;
                    if($article->state ==1){
                        $searchs[$search_id]->open_count++;
                    }
                    if(($article->created_at->timestamp >= $today_timestamp) && ($article->created_at->timestamp  < $end_of_day_timestamp)){
                        $searchs[$search_id]->today_count++;
                    }elseif(($article->created_at->timestamp > $yesterday_timestamp) && ($article->created_at->timestamp  < $today_timestamp)){
                        $searchs[$search_id]->yesterday_count++;
                    }
                }
            }
        }

        //태그등록 자동완성용 태그들
        $alltags = Tag::select('name')
            ->get();

        foreach($alltags as $alltag){
            $temp[] = $alltag->name;
        }
        if(isset($temp)){
            $alltags =json_encode(array_values(array_unique($temp)));
        }else{
            $alltags = json_encode([]);
        }

        //태그검색 자동완성용 태그들
        $tags = CollectBatch::where('type',$params['type'])
            ->where('board',$params['board'])
            ->where('gender',$params['gender'])
            ->get();

        if(count($tags)==0){
            $tag_temp = [];
        }else {
            foreach ($tags as $tag) {
                $tag_temp[] = $tag->search;
            }
        }
        $tags = json_encode(array_values(array_unique($tag_temp)));

        //Crawling_Standard
        $standards = CollectRule::all();
        if(count($standards) == 0){
            $temp =new \stdClass();
            $temp->like_cnt =0;
            $temp->view_cnt =0;
            $temp->get_cnt =0;
            $standards[] = $temp;
        }
        // 배치 실행 여부 체크
        $batch_status = Redis::EXISTS('batchrun');

        //유투브 채널 크롤링 상태 체크
        $youtube_crawling_check = DB::table('collect_batches')
            ->where('board', 'youtube')
            ->where('type', 'channel')
            ->where('state', 3)
            ->count();

        //인스타그램 계정 크롤링 상태 체크
        $batch = Batch::create()
            ->setUrl(config('azure.batch.url'))
            ->setKey(config('azure.batch.key'))
            ->setAccount(config('azure.batch.account'))
            ->setApiVersion(config('azure.batch.api_version'));

        $job_list= $batch->listjob();

        //작업 다 불러와서 확인하면 너무 느림 크롤링 문제생기면 개별문제보단 단체로 문제생기니 5개정도만뽑아서 검사함
        $job_list=array_slice($job_list,0,5);
        $instagram_account_crawling_state = true;
        if(count($job_list)>0){
            foreach($job_list as $job){
                if(isset($batch->listTask($job->id)[0])){
                    $crawling_state= $batch->listTask($job->id)[0];
                    if(isset($crawling_state->executionInfo->result) &&
                        $crawling_state->executionInfo->result == 'failure'){
                        $instagram_account_crawling_state = false;
                        $instagram_account_crawling_error_id =  $crawling_state->id;
                        break;
                    }
                }
            }
        }
        $instagram_account_crawling_state=false;
        $instagram_account_crawling_error_id = 'batch_error';
        return view('crawling_batch/index')->with([
            'title' =>  'CrawlingBatch',
            'crawling_batch_menu' => 'active',
            'params'    =>  $params,
            'all_tag_list' =>   $alltags,
            'tag_list'  =>  $tags,
            'searchs'   =>  $searchs,
            'standards' =>  $standards[0],
            'batch_status' => $batch_status,
            'youtube_crawling_state' => ($youtube_crawling_check > 0) ? false : true,
            'instagram_account_crawling_state'  =>  $instagram_account_crawling_state,
            'instagram_account_crawling_error_id'   =>  isset($instagram_account_crawling_error_id) ? $instagram_account_crawling_error_id:''
        ]);
    }

    public function delete(Request $request)
    {
        $validator = $this->validate($request,[
            'check_item'=>'required'
        ]);
        $params = [
            'check_items' => $request->input('check_item')
        ];
            CollectBatch::
            find($params['check_items'][0])
            ->delete();
    }

    public function state_update(Request $request){

        $validator = $this->validate($request,[
            'check_item'=>'required'
        ]);

        $params = [
            'state' =>  $request->input('state'),
            'check_items' => $request->input('check_item')
        ];

        CollectBatch::find($params['check_items'][0])
            ->update([
                'state' => $params['state']
            ]);

//                todo 스케줄러 -> 잡
//        $collect_batch =  CollectBatch::find($params['check_items'][0]);

//        if($collect_batch->board == 'instagram' && $collect_batch->type == 'account'){
//            $batch = Batch::create()
//                ->setUrl(config('azure.batch.url'))
//                ->setKey(config('azure.batch.key'))
//                ->setAccount(config('azure.batch.account'))
//                ->setApiVersion(config('azure.batch.api_version'));
//
//            //비활성화 ==> 스케줄 삭제
//            if($params['state'] == 0){
//                $replace_search = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $collect_batch->search);
//                $batch->deleteJobschedule("task_{$replace_search}");
//                //활성화 ==> 스케줄 재생성
//            }elseif ($params['state'] == 1){
//                $replace_search = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $collect_batch->search);
//                $job_schedule_params=[
//                    'scheduleId' => "task_{$replace_search}",
//                    'jobId' => "jobRe{$replace_search}",
//                    'poolId' => config('azure.batch.pool_id'),
//                    'commandLine' => "sudo runuser -l tdi -c 'python3 /home/tdi/crt9_insta_crawler_once/schedulev1.py {$collect_batch->gender} {$collect_batch->search}'",
//                    'interval' => "PT1H"
//                ];
//                $add= $batch->addJobschedule($job_schedule_params);
//                if(!$add){
//                    return response()->json([
//                        'result' => 'fail',
//                        'code' => 0,
//                        'message' => 'Failed to create product',
//                        'data' => new \stdClass(),
//                    ], 500);
//                }
//            }
//        }
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request,[
            'input' =>  'required'
        ]);

        $params = [
            'input' =>  $request->input('input'),
            'board' =>  $request->input('board'),
            'type'  =>  $request->input('type'),
            'gender'=>  $request->input('gender')
        ];

        if($params['input'] == null || trim($params['input']=='')){}
        else {
            $query= CollectBatch::where('board',$params['board'])
                ->where('search',$params['input'])
                ->where('type',$params['type']);
            if($query->count() ==0 ){
                CollectBatch::create([
                    'search'    =>  $params['input'],
                    'board'     =>  $params['board'],
                    'type'      =>  $params['type'],
                    'gender'    =>  $params['gender'],
                    'last_collected_at' =>  Carbon::yesterday(),
                    'once'      =>  0
                ]);
//                todo 스케줄러 -> 잡
//                if($params['board'] == 'instagram' && $params['type'] == 'account'){
//                    // azure 배치 생성
//                    $batch = Batch::create()
//                        ->setUrl(config('azure.batch.url'))
//                        ->setKey(config('azure.batch.key'))
//                        ->setAccount(config('azure.batch.account'))
//                        ->setApiVersion(config('azure.batch.api_version'));
//                    $replace_search = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $params['input']);
//                    $job_schedule_params=[
//                        'scheduleId' => "task_{$replace_search}",
//                        'jobId' => "jobRe{$replace_search}",
//                        'poolId' => config('azure.batch.pool_id'),
//                        'commandLine' => "sudo runuser -l tdi -c 'python3 /home/tdi/crt9_insta_crawler_once/schedulev1.py {$params['gender']} {$params['input']}'",
//                        'interval' => "PT1H"
//                    ];
//                    $add= $batch->addJobschedule($job_schedule_params);
//                    if(!$add){
//                        $get_parameter = [
//                            'gender'=>  $request->input('gender'),
//                            'board' =>  $request->input('board'),
//                            'type'  =>  $request->input('type')
//                        ];
//                        return redirect('/admin/collect_batches?'.http_build_query($get_parameter, 'a', '&'))
//                            ->with(['message' => 'Failed to create product']);
//                    }
//                }
            }else{
                $collect_batch = $query->get()->last();
                $get_parameter=[
                    'gender'=>  $collect_batch->gender,
                    'board' =>   $collect_batch->board,
                    'type'  =>  $collect_batch->type,
                     'tags'    =>  $collect_batch->search
                ];
                return  redirect('/admin/collect_batches?'.http_build_query($get_parameter, 'a', '&'))->with(['alert'=>'이미 있는 키워드입니다.']);
            }
        }
        $get_parameter = [
            'gender'=>  $request->input('gender'),
            'board' =>  $request->input('board'),
            'type'  =>  $request->input('type')
        ];
        return redirect('/admin/collect_batches?'.http_build_query($get_parameter, 'a', '&'));
    }

    public function execute()
    {

        $exec = "sudo -u nsmg -S /var/www/html/pinxy_backend/cron_crawling.sh";
        exec($exec,$out, $rcode);

        sleep(3);

        return redirect('/admin/collect_batches')->with('alert', '배치 실행을 시작 했습니다');
    }

    public function rule_update(Request $request){
        $params =[
            'like_cnt' => $request->input('like_cnt'),
            'view_cnt'  =>  $request->input('view_cnt'),
            'get_cnt'   =>  $request->input('get_cnt'),
            'board' =>  $request->input('board','instagram'),
            'type'  =>  $request->input('type','hashtag'),
            'search'    =>  $request->input('search'),
            'gender'    =>  $request->input('gender',1)
        ];
        $del['like_cnt']=$params['like_cnt'];
        $del['view_cnt']=$params['view_cnt'];
        $del['get_cnt']=$params['get_cnt'];
        DB::table('collect_rules')->update($del);

        $get_parameter = [
            'like_cnt' => $request->input('like_cnt'),
            'view_cnt'  =>  $request->input('view_cnt'),
            'get_cnt'   =>  $request->input('get_cnt'),
            'gender'=>  $request->input('gender'),
            'board' =>  $request->input('board'),
            'type'  =>  $request->input('type')
        ];
        return redirect('/admin/collect_batches?'.http_build_query($get_parameter, 'a', '&'));
    }

    public function test()
    {
        $url = "https://www.instagram.com/leeseol00";

        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_POST, false);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($s);
        $status_code = curl_getinfo($s, CURLINFO_HTTP_CODE);
        echo curl_error($s); echo "\n";

        print_r($result);
        print_r($status_code);
        curl_close($s);
    }

    function search_search_word($word, $array) {
        foreach ($array as $key => $val) {
            if (strtoupper($val['search']) === strtoupper($word)) {
                return $key;
            }
        }
        return -1;
    }
}
