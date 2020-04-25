<?php


namespace App\Console\Commands;

use App\Crawler;
use App\Lib\Channel\Factory\ChannelFactory;
use Carbon\Carbon;
use App\CrawlerLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrawlerChannelCommand extends Command
{
    protected $signature = 'crawler:channel {sns_name}';
    protected $description = 'get channel Contents';
    protected $sns_name;

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
       $this->sns_name = $this->argument('sns_name');

         $crawlerList = Crawler::whereState('play')->where('type','=',$this->sns_name)->get();
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

         // 크롤러 실행 결과 수집
         CrawlerLog::create([
             'status' => 'S'
         ]);
     }
}
