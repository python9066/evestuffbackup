<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;
use utils\Helper\Helper;
use utils\Timerhelper\Timerhelper;

class UpdateTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:timers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will update structure vul windows';

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
     * @return int
     */
    public function handle()
    {

        Userlogging::created(['url' => "demon windows", 'user_id' => 9999999999]);
        $status = Helper::checkeve();
        if ($status == 1) {
            Timerhelper::update();
        }
    }
}
