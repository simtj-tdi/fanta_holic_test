<?php


namespace App\Console\Commands;

use App\Crawler;
use App\Lib\Channel\Factory\ChannelFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrawlerCommand extends Command
{
    protected $signature = 'crawler:get {crawlerId}';
    protected $description = 'get channel Contents';
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
        $this->crawlerId = $this->argument('crawlerId');
        $this->info('start '.\Illuminate\Support\Carbon::now('asia/seoul')->format('Y-m-d H:i:s'));
        $this->info(__METHOD__ . ' - crawler start -  id : ' . $this->crawlerId);
        $crawler = Crawler::find($this->crawlerId);
        $this->info(__METHOD__.' - crawler info -'.json_encode($crawler));
        $channel = new ChannelFactory($crawler);
        $channel->getChannelContents();
    }
}
