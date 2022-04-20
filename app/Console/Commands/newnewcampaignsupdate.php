<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;
use utils\Campaignhelper\Campaignhelper;

class newnewcampaignsupdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:newnewfix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Userlogging::create(['url' => 'demon campagin new new', 'user_id' => 9999999999]);

        Campaignhelper::update();
    }
}
