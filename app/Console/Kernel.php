<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\RetryFailedJobs::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
	//Log::info('Executing schedule crontab');
    
        $schedule->command('queue:retry all')->everyThirtyMinutes();
        $schedule->command('queue:work database --queue=default --tries=3 --sleep=3 --timeout=30 --daemon ')->everyMinute()->withoutOverlapping();
        $schedule->command('queue:restart')->hourly();
	//$schedule->exec('php artisan queue:retry-failed');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
