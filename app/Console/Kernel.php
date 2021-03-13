<?php

namespace App\Console;

use App\Console\Commands\JibitGenerateToken;
use App\Console\Commands\JibitPayGenerate;
use App\Console\Commands\JibitPayRefresh;
use App\Console\Commands\RefreshJibitToken;
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
        RefreshJibitToken::class,
        JibitGenerateToken::class,
        JibitPayGenerate::class,
        JibitPayRefresh::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('jibit:refresh')->everyMinute();
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
