<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;
use utils\Helper\Helper;
use utils\NewCampaignhelper\NewCampaignhelper;

class newCampaignUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:newCampaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New command to update sov stuff';

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
        // Userlogging::create(['url' => 'demon newCampaign', 'user_id' => 9999999999]);
        // $status = Helper::checkeve();
        // if ($status == 1) {
        //     NewCampaignhelper::newUpdate();
        // };
        NewCampaignhelper::newUpdate();
    }
}
