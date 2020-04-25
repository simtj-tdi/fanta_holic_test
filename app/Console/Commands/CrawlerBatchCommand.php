<?php

namespace App\Console\Commands;

use App\Lib\Azure\Batch;
use Illuminate\Console\Command;


class CrawlerBatchCommand extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawler batch instagram';
    protected $batch;
    protected $date;
    protected $status;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->batch = Batch::create()
          ->setUrl('https://crt9batch.koreasouth.batch.azure.com')
          ->setKey('Vf5DhOiNJYXjMAX+U5sMcDfaxe8ZfNkdNYImJlKB00D1KL9Bm1+zB2JFnAnaaYxz6kH+JtVPiiT5lbvNa+bDnw==')
          ->setAccount('crt9batch')
          ->setApiVersion('2018-08-01.7.0');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $jobId = sprintf("crawler_insta-%s", now()->format('Ymd'));
      if(empty($this->batch->getJob($jobId))) {
            $params = [
              'jobId' => $jobId,
              'poolId' => 'fanta_holic_crawler_instagram_0'
            ];
            $result = $this->batch->addJob($params);
      }
      $taskId = sprintf("%s-task-%s", $jobId, now()->format('Hi'));

      if (empty($this->batch->getTask($jobId, $taskId)) === false) {
          return false;
      }

      $commandLine = sprintf("php /var/www/fanta_holic_backend/artisan crawler:channel instagram");

      $params = [
          'jobId' => $jobId,
          'taskId' => $taskId,
          'commandLine' => $commandLine,
      ];

      $this->batch->addTask($params);
    }
}
