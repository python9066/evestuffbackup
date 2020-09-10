<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Campaginhelper\Campaginhelper;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;

class UpdateCampagins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:campagins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update all campagins';

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
        $status = Helper::checkeve();
        if ($status == 1){
        $flag = Campaignhelper::update();
        }
    }
}
