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
        $schedule->command('update:campaigns')->everyMinute()->unlessBetween('11:00', '11:10')->withoutOverlapping();
        $schedule->command('update:eveusercount')->everyMinute()->unlessBetween('11:00', '11:10')->withoutOverlapping();
        $schedule->command('clean:coordsheet')->everyMinute()->withoutOverlapping();
        $schedule->command('update:towers')->everyMinute()->withoutOverlapping();
        $schedule->command('update:notifications')->everyMinute()->unlessBetween('11:00', '11:20')->withoutOverlapping();
        $schedule->command('update:stationnotifications')->everyMinute()->withoutOverlapping();
        $schedule->command('update:reconstations')->hourly()->withoutOverlapping();
        $schedule->command('update:timers')->hourly()->unlessBetween('11:00', '11:20')->withoutOverlapping();
        $schedule->command('update:alliances')->twiceDaily(9, 22)->withoutOverlapping();
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
