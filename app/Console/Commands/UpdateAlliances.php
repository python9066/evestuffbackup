<?php

namespace App\Console\Commands;

use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Userlogging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use utils\Alliancehelper\Alliancehelper;
use utils\Helper\Helper;

class UpdateAlliances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:alliances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update alliances and standing';

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


        // $status = Helper::checkeve();
        // if ($status == 1) {
        //     Alliancehelper::updateAlliances();
        // }
        $response =  Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/dev/alliances/?datasource=tranquility");
        $allianceIDs = $response->collect();
        $deads =   Alliance::whereNotIn('id', $allianceIDs)->get();
        foreach ($deads as $dead) {
            Corp::where('alliance_id', $dead->id)->update(['alliance_id' => null]);
            $dead->delete();
        }

        foreach ($allianceIDs as $allianceID) {
            $this->startAlliance($allianceID);
        }
    }

    public function startAlliance($allianceID)
    {



        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/?datasource=tranquility");
        $allianceInfo = $response->collect();
        dd(
            $allianceInfo->get('name')
        );

        Alliance::updatedOrCreate(
            ['id' => $allianceInfo->id],
            [
                'name' => $allianceInfo->name,
                'ticker' => $allianceInfo->ticker,
                'standing' => 0,
                'active' => 1,
                'url' => "https://images.evetech.net/Alliance/" . $allianceID . "_64.png",
                'color' => 0
            ]
        );





        Corp::where('alliance_id', $allianceID)->update(['alliance_id' => null]);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/corporations/?datasource=tranquility");
        $corpIDs = $response->collect();
        // Corp::whereIn('id', $corpIDs)->update(['alliance_id' => $allianceID]);
        foreach ($corpIDs as $corpID) {
            $this->startCorpJob($corpID, $allianceID);
        }
    }

    public function startCorpJob($corpID, $allianceID)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/corporations/" . $corpID . "/?datasource=tranquility");
        $corpInfo = $response->collect();
        Corp::updateOrCreate(
            ['id' => $corpID],
            [
                'alliance_id' => $allianceID,
                'name' => $corpInfo->name,
                'ticker' => $corpInfo->ticker,
                'color' => 0,
                'standing' => 0,
                'active' => 1,
                'url' => "https://images.evetech.net/Corporation/" . $corpID . "_64.png",

            ]
        );
    }
}
