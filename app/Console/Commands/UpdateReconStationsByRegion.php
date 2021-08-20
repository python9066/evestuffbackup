<?php

namespace App\Console\Commands;

use App\Events\StationUpdateCoord;
use Illuminate\Console\Command;
use utils\Notificationhelper\Notifications;

class UpdateReconStationsByRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reconstationsbyregion';

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
        $ids = [
            10000060,
            10000050,
            10000063,
            10000058,
        ];

        foreach ($ids as $id) {
            $stations =  Notifications::reconRegionPull($id);
            foreach ($stations as $station) {
                Notifications::reconRegionPullIdCheck($station);
            }
        }

        $flag = collect([
            'flag' => 1
        ]);
        broadcast(new StationUpdateCoord($flag));
    }
}
