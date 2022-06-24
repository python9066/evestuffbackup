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
        $schedule->command('update:EveEsiStatus')->everyMinute()->withoutOverlapping();
        $schedule->command('update:campaigns')->everyMinute()->withoutOverlapping();
        $schedule->command('update:campaginFix')->everyMinute()->withoutOverlapping();
        $schedule->command('update:newCampaigns')->everyMinute()->withoutOverlapping();
        $schedule->command('update:eveusercount')->everyMinute()->withoutOverlapping();
        $schedule->command('update:opuserlist')->everyMinute();
        $schedule->command('update:standing')->everyTenMinutes();
        $schedule->command('clean:coordsheet')->everyMinute()->withoutOverlapping();
        $schedule->command('update:towers')->everyMinute()->withoutOverlapping();
        $schedule->command('update:notifications')->everyMinute()->withoutOverlapping();
        $schedule->command('update:stationnotifications')->everyMinute()->withoutOverlapping();
        $schedule->command('update:reconstations')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        $schedule->command('update:webway')->everyFiveMinutes();
        $schedule->command('update:timers')->hourly()->withoutOverlapping();
        $schedule->command('update:alliances')->dailyAt('22:00')->withoutOverlapping();
        $schedule->command('clear:remembertoken')->twiceDaily(9, 21)->withoutOverlapping();
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
