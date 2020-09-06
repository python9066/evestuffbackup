<?php

namespace App\Console\Commands;

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
        if ($status == 1){
        $type = 'note';
        Helper::authcheck();
        $data = Helper::authpull($type);
        Notifications::update($data);
        }
    }
}
