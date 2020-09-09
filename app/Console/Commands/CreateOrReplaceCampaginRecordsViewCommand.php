<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceCampaginRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceCampaginRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Campagins';

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
        DB::statement("CREATE VIEW campagin_records AS SELECT campaigns.id AS 'id',
        regions.region_name AS 'region_name',
        regions.id AS 'region_id',
        constellations.constellation_name AS 'constellation_name',
        campaigns.constellation_id AS 'constellation_id',
        systems.system_name AS 'system_name',
        campaigns.system_id AS 'system_id',
        alliances.name AS 'alliance_name',
        campaigns.alliance_id AS 'alliance_id',
        alliances.ticker AS 'alliance_ticker',
        alliances.color AS 'alliance_color',
        alliances.url AS 'alliace_logo',
        campaigns.event_type AS 'event_type',
        items.item_name AS 'item_name',
        campaigns.attackers_score AS 'attackers_score',
        campaigns.defenders_score AS 'defenders_score',
        campaigns.status_id AS 'status_id',
        campagain_statuses.name AS 'status_name'
        FROM campaigns
        JOIN systems ON systems.id = campaigns.system_id
        JOIN constellations ON constellations.id = campaigns.constellation_id
        JOIN alliances ON alliances.id = campaigns.alliance_id
        JOIN items ON items.id = campaigns.event_type
        JOIN regions ON regions.id = systems.region_id
        JOIN campagain_statuses ON campagain_statuses.id = campaigns.status_id
        WHERE (campaigns.attackers_score != 1 OR campaigns.attackers_score != 0) AND campaigns.status_id != 10");
    }
}


