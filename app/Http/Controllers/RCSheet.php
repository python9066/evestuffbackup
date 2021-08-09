<?php

namespace App\Http\Controllers;

use App\Models\Alliance;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\Corp;
use App\Models\Item;
use App\Models\System;

class RCSheet extends Controller
{
    public function RCInput(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            $stationName = $input['name'];
            $timer = Helper::fixtime($input['Expires']);
            $allianceID = Alliance::where('ticker', $input['Ticker'])->first();
            $itemID = Item::where('item_name', $input['Type'])->first();
            $systemID = System::where('system_name', $input['System'])->first();

            if ($input['Timer'] == "Armor") {
                $statusID = 8;
            }

            if ($input['Timer'] == "Hull") {
                $statusID = 9;
            }
            if ($input['Timer'] == "Anchoring") {
                $statusID = 14;
            }


            $check = Station::where(['name' => $input['name'], 'system_id' => $systemID->id, 'alliance_id' => $allianceID->id])->first();
            if ($check) {
                // $checkid = $check["id"];
                $check->update(['station_status_id' => $statusID, 'out_time' => $timer]);
                // dd($check->id);
            } else {



                $reconpull = $this->reconPullbyname($stationName);
                // dd($reconpull);


                if (!$reconpull) {
                    // dd("yoyo");
                    $id = Station::where('id', '<', 10000000000)->max('id');
                    if ($id == null) {
                        $id = 1;
                    } else {
                        $id = $id + 1;
                    }



                    $new = Station::Create(['name' => $input['name'], 'system_id' => $systemID->id, 'alliance_id' => $allianceID->id, 'item_id' => $itemID->id, 'station_status_id' => $statusID, 'out_time' => $timer]);
                    $new->update(['id' => $id]);
                }
            }
            // dd('yo');
        }
    }

    public static function reconPullbyname($stationName)
    {

        $url = "https://recon.gnf.lt/api/structure/" . $stationName;

        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'token' =>  env('RECON_TOKEN', "DANCE")

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);

        $stationdata = Utils::jsonDecode($response->getBody(), true);

        if ($response->getStatusCode() == 200) {
            if ($stationdata == "Error, Structure Not Found") {

                return false;
            } else {

                Station::updateOrCreate(['id' => $stationdata['str_structure_id']], [
                    'name' => $stationdata['str_name'],
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'item_id' => $stationdata['str_type_id'],
                    'text' => null,
                    'station_status_id' => 10,
                    'user_id' => null,
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
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
                    'r_cored' => $stationdata['str_cored'],
                    'show_on_rc' => 1
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    };
                }

                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $item = Item::where('id', $stationdata['str_type_id'])->first();
                $system = System::where('id', $stationdata['str_system_id'])->first();

                return true;
            }
        } else {
            $stationCheck = station::where('name', $stationName)->first();
            if ($stationCheck != null) {
            } else {
                return false;
            }
        };
    }
}
