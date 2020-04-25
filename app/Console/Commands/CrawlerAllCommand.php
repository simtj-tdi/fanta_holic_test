<?php


namespace App\Console\Commands;

use App\Crawler;
use App\CrawlerLog;
use App\Lib\Channel\Factory\ChannelFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Push;
use App\Board;

class CrawlerAllCommand extends Command
{
    protected $signature = 'crawler:all ';
    protected $description = 'get all channel contents';
    protected $crawlerId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $crawlerList = Crawler::whereState('play')->get();
        $cnt =0;

        foreach($crawlerList as $crawler )
        {
            $this->info('start '.\Illuminate\Support\Carbon::now('asia/seoul')->format('Y-m-d H:i:s'));
            $this->info(__METHOD__.' - crawler info -'.json_encode($crawler));

            $channel = new ChannelFactory($crawler);
            $channel->getChannelContents();

            $cnt += $channel::$successCnt;
            $crawler->finaled_at = date('Y-m-d H:i:s');
            $crawler->save();

            //sleep(10);
        }

        //게시물 알림 => 새 게시물이 있는 경우 푸쉬
      /*  $upload_cnt = Board::whereBetween('created_at', [ date("Y-m-d h:i:s" , strtotime("-1 hours") ), date('Y-m-d H:i:s')])->count();

        if($upload_cnt > 0 ){
            Push::create([
                'app'   =>  'bts',
                'batch_type'  =>  'A',
                'managed_type'  =>  'M',
                'user_id'   =>  0,
                'title' =>  '새 게시글이 등록되었습니다.',
                'content'   =>'새 게시글이 등록되었습니다.',
            ]);
        }*/

        //새로 등록된게 한개이상일떄 작업
        if ($cnt > 0) {
            Push::create([
                'app' => 'fantaholic',
                'batch_type' => 'A',
                'managed_type' => 'N',
                'user_id' => 0,
                'title' => '새로운 게시물이 등록되었습니다. ',
                'content' => '새로운 게시물이 등록되었습니다.',
                'tick' => 0,
                'push_type' => 'T',
                'action' => 'A',
                'state' => 'R',
                'start_date' => Carbon::now()->addDays(-1),
            ]);
        }

        // 크롤러 실행 결과 수집
        CrawlerLog::create([
            'status' => 'S',
            'crawler_cnt' => $cnt,
        ]);
    }
}
