<?php

namespace App\Console\Commands;

use App\Events\NotificationNew;
use App\Models\User;
use App\Models\Userlogging;
use Illuminate\Console\Command;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;

class UpdateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will run and check for new notifications';

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

        Userlogging::created(['url' => "NOTES!!!!!", 'user_id' => 9999999999]);
        $status = Helper::checkeve();
        if ($status == 1) {
            $type = "note";
            Helper::authcheck();
            $data = Helper::authpull($type, 0);
            $flag = Notifications::update($data);

            // dd($flag);
            if ($flag['notificationflag'] == 1) {
                broadcast(new NotificationNew($flag['notificationflag']))->toOthers();
            }
        }
    }
}
