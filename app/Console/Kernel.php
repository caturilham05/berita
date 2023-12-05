<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:football-task')->hourly()->sendOutputTo('storage/logs/FootballTask.log');
        $schedule->command('app:motogp-task')->hourly()->sendOutputTo('storage/logs/MotogpTask.log');
        $schedule->command('app:content-detail-task')->hourly()->sendOutputTo('storage/logs/ContentDetailTask.log');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
