<?php

namespace App\Console\Commands;

use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\Station;
use App\Models\WebWay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateWebWayRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:webway';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will get all the route links and jump counts for active systems';

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
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);

        $start_system_id = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);

        WebWay::where('id', '>', 0)->update(['active' => 0]);
        $stations = Station::get();
        $stationSystems = $stations->pluck('system_id');
        $campaigns = Campaign::get();
        $campaginSystems = $campaigns->pluck('system_id');

        $systemIDs = $stationSystems->merge($campaginSystems);
        $systemIDs = $systemIDs->unique();
        $systemIDs = $systemIDs->values();
        WebWay::whereIn('system_id', $systemIDs)->update(['active' => 1]);
        WebWay::where('active', 0)->delete();

        foreach ($systemIDs as $end_system_id) {
            updateWebwayJob::dispatch($start_system_id, $end_system_id)->onQueue('webway');
        }
    }
}
