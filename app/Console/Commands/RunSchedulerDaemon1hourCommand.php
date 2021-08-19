<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use utils\Campaignhelper\Campaignhelper;

class RunSchedulerDaemon1hourCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daemon1hour {--sleep=3600}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a daemon that will automatically run the Laravel schedule every 60 seconds';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        while (true) {
            // $this->call('schedule:run');
            sleep($this->option('sleep'));
            Artisan::call('update:reconstations');
            Artisan::call('update:timers');
        }
    }
}
