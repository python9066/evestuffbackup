<?php

namespace App\Console\Commands;

use App\Events\StationUpdateCoord;
use App\Jobs\ReconRegionPullJob;
use App\Models\HotRegion;
use App\Models\Station;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateReconStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reconstations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will pull all station data from recon';

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
        activity()->disableLogging();
        Userlogging::create(['url' => 'demon recon station', 'user_id' => 9999999999]);
        dubp();
        $s = Station::get();
        foreach ($s as $s) {

            $s->update(['import_flag' => 0]);
        }

        Userlogging::create(['url' => 'demon region', 'user_id' => 9999999999]);
        $ids = HotRegion::where('update', 1)->pluck('region_id');

        foreach ($ids as $id) {
            $stations = reconRegionPull($id);
            foreach ($stations as $station) {
                ReconRegionPullJob::dispatch($station);
            }
        }

        // $flag = collect([
        //     'flag' => 1,
        // ]);
        // broadcast(new StationUpdateCoord($flag));
        // reconUpdate();
        // activity()->enableLogging();
    }
}
