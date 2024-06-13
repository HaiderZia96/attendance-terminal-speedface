<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\Jobs\FetchEmployeeDetail::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //Generating Sitemap daily
        $schedule->command('generate:sitemap')->weekly();

        $schedule->command('app:fetch-employee-detail')
            ->everyMinute();
        // $schedule->command('inspire')->hourly();


    }
//    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
//    {
//        // this command will run every 30 seconds
//        $shortSchedule->command('queue:work --timeout=36000 --once')->everySeconds(60);
//
//    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
