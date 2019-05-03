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
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        #$schedule->call('\App\Http\Controllers\SchedulingController@UpdateImage')->everyMinute();
        #$schedule->call('\App\Http\Controllers\SchedulingController@crawInfoDomain')->everyMinute();
        $schedule->call('\App\Http\Controllers\SchedulingController@getWhoisDomain')->everyMinute();
        $schedule->call('\App\Http\Controllers\SchedulingController@getRankDomain')->everyMinute();
        $schedule->call('\App\Http\Controllers\SchedulingController@getIpRecord')->everyMinute();
        $schedule->call('\App\Http\Controllers\SchedulingController@updateCountry')->everyMinute();
        $schedule->call('\App\Http\Controllers\SchedulingController@indexPostElasticsearch')->everyMinute();
        if(config('app.env')!='local'){
            $schedule->call('\App\Http\Controllers\SchedulingController@keywordCraw')->cron('*/2 * * * *');
            $schedule->call('\App\Http\Controllers\SchedulingController@keywordSuggest')->cron('*/2 * * * *');
        }
//        $schedule->call('\App\Http\Controllers\SchedulingController@crawImageSearch')->cron('*/5 * * * *');
//        $schedule->call('\App\Http\Controllers\SchedulingController@crawVideoSearch')->cron('*/5 * * * *');
        $schedule->call('\App\Http\Controllers\SchedulingController@crawNews')->cron('*/2 * * * *');
    }
}
