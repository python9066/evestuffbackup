<?php

namespace App\Jobs;

use App\Events\StationWatchListSettingPageUpdate;
use App\Listeners\SendStationWatchListSettingPageUpdate;
use App\Models\Station;
use App\Models\StationItemJoin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Utils;
use App\Models\Corp;
use Illuminate\Support\Facades\Http;
use App\Models\Alliance;
use App\Models\StationItems;
use Carbon\Carbon;

class ReconRegionPullJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stationID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stationID)
    {
        $this->stationID = $stationID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        activity()->disableLogging();
        $current_time = now();
        $x_minutes_ago = $current_time->subMinutes(5);
        $station = Station::whereId($this->stationID)->first();
        if ($station) {
            if ($station->updated_at <= $x_minutes_ago) {
                reconRegionPullIdCheck($this->stationID);
                sendStationListUpdateToWatchListPage($this->stationID);
            }
        } else {
            reconRegionPullIdCheck($this->stationID);
            sendStationListUpdateToWatchListPage($this->stationID);
        }
        activity()->enableLogging();
    }

    public function getStation($id)
    {


        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structure/' . $id;
        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($stationdata == 'Error, Structure Not Found') {
            $s = Station::find($id)->get();
            $s = Station::where('id', $id)->first();
            $s->delete();
            $s = StationItemJoin::where('station_id', $id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
        } else {
            $core = 0;
            $standing = 0;
            $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
            if (!$corp) {
                $corpPull = 0;
                do {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'evestuff.online python9066@gmail.com',
                    ])->get('https://esi.evetech.net/latest/corporations/' . $stationdata['str_owner_corporation_id'] . '/?datasource=tranquility');
                    if ($response->successful()) {
                        $corpPull = 3;
                        $corpInfo = $response->collect();
                        Corp::updateOrCreate(
                            ['id' => $stationdata['str_owner_corporation_id']],
                            [
                                'alliance_id' => null,
                                'name' => $corpInfo->get('name'),
                                'ticker' => $corpInfo->get('ticker'),
                                'color' => 0,
                                'standing' => 0,
                                'active' => 1,
                                'url' => 'https://images.evetech.net/Corporation/' . $stationdata['str_owner_corporation_id'] . '_64.png',

                            ]
                        );
                        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                    } else {
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        $corpPull++;
                    }
                } while ($corpPull != 3);
            }
            $alliance = Alliance::where('id', $corp->alliance_id)->first();
            if ($alliance) {
                if ($corp->standing > $alliance->standing) {
                    $standing = $corp->standing;
                } else {
                    $standing = $alliance->standing;
                }
            } else {
                $standing = $corp->standing;
            }

            if ($stationdata['str_cored'] == 'Yes') {
                $core = 1;
            }

            $s = StationItemJoin::where('station_id', $id)->get();
            foreach ($s as $s) {
                $s->delete();
            }

            Station::updateOrCreate(['id' => $id], [
                'name' => $stationdata['str_name'],
                'standing' => $standing,
                'r_hash' => $stationdata['str_structure_id_md5'],
                'corp_id' => $stationdata['str_owner_corporation_id'],
                'r_updated_at' => $stationdata['updated_at'],
                'r_fitted' => $stationdata['str_has_no_fitting'],
                'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                'r_hyasyoda' => $stationdata['str_hyasyoda'],
                'r_invention' => $stationdata['str_invention'],
                'r_manufacturing' => $stationdata['str_manufacturing'],
                'r_research' => $stationdata['str_research'],
                'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                'r_biochemical' => $stationdata['str_biochemical'],
                'r_hybrid' => $stationdata['str_hybrid'],
                'r_moon_drilling' => $stationdata['str_moon_drilling'],
                'r_reprocessing' => $stationdata['str_reprocessing'],
                'r_point_defense' => $stationdata['str_point_defense'],
                'r_dooms_day' => $stationdata['str_dooms_day'],
                'r_guide_bombs' => $stationdata['str_guide_bombs'],
                'r_anti_cap' => $stationdata['str_anti_cap'],
                'r_anti_subcap' => $stationdata['str_anti_subcap'],
                'r_t2_rigged' => $stationdata['str_t2_rigged'],
                'r_cloning' => $stationdata['str_cloning'],
                'r_composite' => $stationdata['str_composite'],
                'r_cored' => $core,
                'system_id' => $stationdata['str_system_id'],
                'item_id' => $stationdata['str_type_id'],
                'added_from_recon' => 1,
                'import_flag' => 1,

            ]);
            $stationNew = Station::where('id', $id)->first();
            $stationNew->touch();

            $status_id = Station::where('id', $id)->value('station_status_id');
            if ($status_id == 7) {
                $s = Station::where('id', $id)->first();
                $s->station_status_id = 16;
                $s->save();
            }

            $station = Station::where('id', $id)->first();
            if ($station->show_on_rc != 1 && $station->show_on_rc_move != 1) {
                $station->show_on_coord = 1;
                $station->save();

                if ($station->station_status_id == 10) {
                    $station = Station::where('id', $id)->first();
                    $station->station_status_id = 1;
                    $station->save();
                }
            }

            if ($stationdata['str_has_no_fitting'] != null) {
                if ($stationdata['str_has_no_fitting'] != 'No Fitting') {
                    $s = StationItemJoin::where('station_id', $id)->get();
                    foreach ($s as $s) {
                        $s->delete();
                    }
                    if ($stationdata['str_fitting']) {
                        $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                        foreach ($items as $item) {
                            StationItems::where('id', $item['type_id'])->get()->count();
                            if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                            }
                            StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $id]);
                        }
                    }
                }
            }
        }
    }
}
