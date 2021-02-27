<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceChillStationRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceChillStationRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for ChillStations';

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
        DB::statement("CREATE VIEW chill_station_records AS SELECT chill_stations.id AS 'id',
       chill_stations.name AS 'station_name',
       chill_stations.system_id AS 'system_id',
       systems.system_name AS 'system_name',
       constellations.constellation_name AS 'constellation_name',
       regions.region_name AS 'region_name',
       chill_stations.item_id AS 'item_id',
       if(items.id = 35840 , 'Cyno Beacon', if(items.id = 37534 , 'Cyno Jammer', if(items.id = 35841, 'Jump Gate', items.item_name))) AS 'item_name',
       chill_stations.user_id AS 'user_id',
       s.name AS 'user_name',
       chill_stations.gunner_id AS 'gunner_id',
       g.name AS 'gunner_name',
       chill_stations.station_status_id AS 'station_status_id',
       station_statuses.name AS 'station_status_name',
       if(chill_stations.out_time IS NULL,chill_stations.timestamp, chill_stations.out_time) AS 'timestamp',
       chill_stations.out_time AS 'out_time',
       alliances.name AS 'alliance_name',
       alliances.ticker AS 'alliance_ticker',
       chill_stations.text AS 'text',
       chill_stations.notes AS 'notes',
       chill_stations.attack_notes AS 'attack_notes',
       chill_stations.attack_adash_link AS 'attack_adash_link',
       if(((chill_stations.attack_notes IS NULL) AND (chill_stations.attack_adash_link IS NULL)),0,1) AS under_attack,
       chill_stations.repair_time AS 'repair_time',
       alliances.standing AS 'standing',
       alliances.url AS 'url'
       FROM stations
       JOIN systems ON systems.id = chill_stations.system_id
       JOIN corps ON corps.id = chill_stations.corp_id
       JOIN alliances on alliances.id = corps.alliance_id
       JOIN constellations ON constellations.id = systems.constellation_id
       JOIN regions ON regions.id = systems.region_id
       JOIN items ON items.id = chill_stations.item_id
       JOIN station_statuses ON station_statuses.id = chill_stations.station_status_id
       LEFT JOIN users AS s ON s.id = chill_stations.user_id
       LEFT JOIN users AS g ON g.id = chill_stations.gunner_id
       WHERE chill_stations.station_status_id != 10");
    }
}
