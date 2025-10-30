<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use \Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SitemapCreate::class,		
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    { 
        $fingerprintLog = storage_path() . '/fingerprint.log';
        $attendancesLog = storage_path() . '/Attendances.log';
        $sessionLog = storage_path() . '/GenerateSession.log';
        $reminderScheduleLog = storage_path() . '/StudentReminder.log';

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $lastweek = Carbon::today()->subDays(7)->toDateString();

        //create sitemap for the web
        $schedule->command('sitemap:create')
                ->hourly();	    
                                
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
