<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceStationRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceStationRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Stations';

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
        DB::statement("CREATE VIEW station_records AS SELECT stations.id AS 'id',
       stations.name AS 'station_name',
       stations.system_id AS 'system_id',
       systems.system_name AS 'system_name',
       constellations.constellation_name AS 'constellation_name',
       regions.region_name AS 'region_name',
       stations.item_id AS 'item_id',
       items.item_name AS 'item_name',
       stations.user_id AS 'user_id',
       s.name AS 'user_name',
       stations.gunner_id AS 'gunner_id',
       g.name AS 'gunner_name',
       stations.station_status_id AS 'station_status_id',
       station_statuses.name AS 'station_status_name',
       if(stations.out_time IS NULL,stations.timestamp, stations.out_time) AS 'timestamp',
       stations.text AS 'text',
       stations.repair_time AS 'repair_time',
       if(corps.color > alliances.color, alliances.color, corps.color) AS 'color'
       FROM stations
       JOIN systems ON systems.id = stations.system_id
       JOIN corps ON corps.id = stations.corp_id
       JOIN alliances on alliances.id = corps.alliance_id
       JOIN constellations ON constellations.id = systems.constellation_id
       JOIN regions ON regions.id = systems.region_id
       JOIN items ON items.id = stations.item_id
       JOIN station_statuses ON station_statuses.id = stations.station_status_id
       LEFT JOIN users AS s ON s.id = stations.user_id
       LEFT JOIN users AS g ON g.id = stations.gunner_id
       WHERE stations.station_status_id != 10");
    }
}
