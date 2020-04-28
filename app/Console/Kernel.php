<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrawlerCommand::class,
        Commands\CrawlerAllCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     *
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('crawler:all')->timezone('asia/seoul')->hourly();
        //$schedule->command('crawler:channel youtube')->timezone('asia/seoul')->hourly();
        //$schedule->command('crawler:batch')->timezone('asia/seoul')->hourly();
        $schedule->command('crawler:channel twitter')->timezone('asia/seoul')->hourlyAt(10);
        $schedule->command('crawler:channel vlive')->timezone('asia/seoul')->hourlyAt(15);
        $schedule->command('crawler:channel news')->timezone('asia/seoul')->hourlyAt(50);

        //크롤링 체크
        $schedule->command('crawler:check')->timezone('asia/seoul')->hourlyAt(20)->withoutOverlapping()->between('9:00', '23:00');

        //push
        //개별 발송
        $schedule->command('push:worker P')
            ->everyMinute();

        // 전체 발송
//        $schedule->command('push:worker A')
//            ->everyMinute()
//            // 중복 실행 방지
//            ->withoutOverlapping();

        // 전체 발송
//        $schedule->command('push:worker N')
//            ->everyMinute()
//            // 중복 실행 방지
//            ->withoutOverlapping()->between('9:00', '23:00');

        $schedule->command('push:worker Country')->timezone('Asia/Seoul')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
