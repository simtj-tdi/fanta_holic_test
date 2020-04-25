<?php

namespace App\Console\Commands;

use App\YoutubeDeveloperKey;
use Illuminate\Console\Command;

class YoutubeKeyResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'developkey:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'developkey count reset';

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
        $this->reset();
    }

    protected function reset(){
       YoutubeDeveloperKey::where('id','>',0)->update([
           'state'=>1,
           'count'=> 0
       ]);
    }
}
