<?php

namespace App\Console\Commands;

use App\Events\NotificationNew;
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
        $status = Helper::checkeve();
        if ($status == 1) {
            $type = "note";
            $ok = Helper::authcheck();
            if ($ok == 1) {
                $data = Helper::authpull($type);
                echo "hi - "
                $flag = Notifications::update($data);
                // dd($flag);
                if ($flag == 1) {
                    broadcast(new NotificationNew($flag))->toOthers();
                }
            }
        }
    }
}
