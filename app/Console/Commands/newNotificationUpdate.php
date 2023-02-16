<?php

namespace App\Console\Commands;

use App\Events\NotificationNew;
use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class newNotificationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:newnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will run and check for new notifications';

    /**
     * Create a new commadwdwadawdwand instance.cscscs
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
        Userlogging::create(['url' => 'demon notes', 'user_id' => 9999999999]);
        // $check = EveEsiStatus::where('route', '/characters/{character_id}/notifications/')->first();
        $check = true;
        // if ($check->status = 'green') {
        if ($check) {
            $type = 'note';
            $data = authpull($type, 0);
            dd("test");
            $flag = notificationUpdate($data);

            if ($flag['notificationflag'] == 1) {
                broadcast(new NotificationNew($flag['notificationflag']))->toOthers();
            }
        }
    }
}
