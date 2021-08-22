<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;
use App\Events\CampaignChanged;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignUser;
use App\Models\Userlogging;

class UpdateCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update all campaign data';

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
    // public function handle()
    // {
    //     $status = Helper::checkeve();
    //     if ($status == 1) {
    //         $flag = Campaignhelper::update();
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();
    //         }
    //     }
    // }

    // public function handle()
    // {
    //     $status = Helper::checkeve();
    //     if ($status == 1) {
    //         $request = Campaignhelper::update();
    //         $flag = $request[0];
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();
    //         }
    //         $flag = null;
    //         $check = $request[1];
    //         foreach ($check as $check) {
    //             Campaignhelper::removeNode($check);
    //             $flag = collect([
    //                 'flag' => 4,
    //                 'id' => $check
    //             ]);
    //             broadcast(new CampaignSystemUpdate($flag))->toOthers();
    //             // }
    //         }
    //     }


    // }

    public function handle()
    {
        Userlogging::create(['url' => "demon!!!!!!!!", 'user_id' => 999999999999999]);
        $status = Helper::checkeve();
        if ($status == 1) {
            Campaignhelper::update();
        };
    }
}
