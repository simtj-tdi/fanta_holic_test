<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CrawlerLog;
use App\Push;
use App\Board;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CrawlerCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check crawler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      $logs = CrawlerLog::whereBetween('created_at',[Carbon::now()->addhour(-1),Carbon::now()])->get()->last();
        $new_cnt = Board::select('type', DB::raw('count(*) as cnt'))->where('created_at','>',Carbon::now()->addhour(-1))->where('type','!=','news')->groupBy('type')->get();
        $new_news_cnt = Board::select('type', DB::raw('count(*) as cnt'))->where('validation_at','>',Carbon::now()->addhour(-1))->where('type','=','news')->groupBy('type')->get();


      //$cnt = $new_cnt[0]->cnt;
      $send_str = "[크롤링 현황]\n";
      $new_push_flag = false;
      foreach($new_cnt as $cnt){
        if(!$logs){
          $send_str .= "[fanta_holic] [Error!!] 크롤링 수집이 정상적으로 수행되지 않았습니다.\n";
        }else{
          $send_str .= "[fanta_holic] - [".$cnt['type']."] 컨텐츠 ".$cnt['cnt']."개\n";
          $new_push_flag = true;
        }
      }

      if(isset($new_news_cnt[0]->cnt)){
          $send_str .= "[fanta_holic] - [news] 컨텐츠 ".$new_news_cnt[0]->cnt."개\n";
          $new_push_flag = true;
      }

      if($new_push_flag){
        Push::create([
            'app' => 'fantaholic',
            'batch_type' => 'N',
            'managed_type' => 'M',
            'user_id' => 0,
            'title' => '새로운 게시물이 등록되었습니다. ',
            'content' => '새로운 게시물이 등록되었습니다.',
            'tick' => 0,
            'push_type' => 'T',
            'action' => 'A',
            'state' => 'R',
            'start_date' => Carbon::now(),
        ]);
      }

  		$query_array = array(
  			'chat_id' => '-1001321023161',
  			'text' => $send_str,
  		);

  		// URL
  		$request_url = "https://api.telegram.org/bot839567660:AAF2KAiL2QhAPmxRKf5OAlqOVI9vuhO1w70/sendmessage?" . http_build_query($query_array);
  		$curl_opt = array(
  				CURLOPT_RETURNTRANSFER => 1,
  				CURLOPT_URL => $request_url,
  			);

  		// curl
  		//$curl = curl_init();
  		//curl_setopt_array($curl, $curl_opt);
  		//var_dump(curl_exec($curl));

      //dd($send_str);

    }
}
