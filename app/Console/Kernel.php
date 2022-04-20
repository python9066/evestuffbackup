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
        Commands\UpdateNotifications::class,
        Commands\UpdateAlliances::class,
        Commands\UpdateCampaigns::class,
        Commands\UpdateTimers::class,
        Commands\ClearRememberToken::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('update:campaigns')->everyMinute();
        $schedule->command('update:newnewfix')->everyMinute();
        $schedule->command('update:newCampaigns')->everyMinute();
        $schedule->command('update:eveusercount')->everyMinute();
        $schedule->command('clean:coordsheet')->everyMinute();
        $schedule->command('update:towers')->everyMinute();
        $schedule->command('update:notifications')->everyMinute()->unlessBetween('11:00', '11:20');
        $schedule->command('update:stationnotifications')->everyMinute();
        $schedule->command('update:reconstations')->hourly();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        $schedule->command('update:webway')->everyFiveMinutes();
        $schedule->command('update:standing')->everyTenMinutes();
        $schedule->command('update:timers')->hourly()->unlessBetween('11:00', '11:20');
        $schedule->command('update:alliances')->dailyAt('22:00');
        $schedule->command('clear:remembertoken')->twiceDaily(9, 21);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
