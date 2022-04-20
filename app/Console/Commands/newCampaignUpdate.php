<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;

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
        $status = Helper::checkeve();
        if ($status == 1) {
            Campaignhelper::newUpdate();
        };
    }
}
