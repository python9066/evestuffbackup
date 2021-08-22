<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;
use App\Events\CampaignChanged;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignUser;
use App\Models\Userlogging;
use utils\Notificationhelper\Notifications;

class UpdateStationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stationnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check station notification status';

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

        Userlogging::create(['url' => 'demon station note', 'user_id' => 9999999999]);
        Notifications::stationNotificationCheck();
    }
}
