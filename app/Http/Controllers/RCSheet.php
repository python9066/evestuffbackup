<?php



namespace App\Http\Controllers;

use App\Events\RcSheetUpdate;
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
use App\Models\Logging;
use App\Models\System;
use Illuminate\Support\Facades\Auth;

class RCSheet extends Controller
{
    public function RCInput(Request $request)
    {

        Station::where('show_on_rc', 1)->update(['show_on_rc' => 5]);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            // echo  $input['structure_name'];
            if ($input['is_hostile'] != null) {
                if ($this->invalidStructure($input['structure_type']['type_id'])) {
                    // dd($input);
                    $newStation = 0;
                    $skip = 0;
                    $corpIDID = 0;
                    $allianceIDID = 0;
                    $stationName = $input['structure_name'];
                    $timer = Helper::fixtime($input['timer_expires']);
                    $corpID = Corp::where('ticker', $input['owning_corp_ticker'])->first();
                    $allianceID = Alliance::where('ticker', $input['owning_alliance_ticker'])->first();
                    $alliance_count = Alliance::where('ticker', $input['owning_alliance_ticker'])->count();
                    $corp_count = Corp::where('ticker', $input['owning_corp_ticker'])->count();
                    if ($corp_count > 0) {
                        $corpIDID = $corpID->id;
                        $allianceIDID = $corpID->id;
                    } else {
                        if ($alliance_count > 0) {
                            $allianceIDID = $allianceID->id;
                        }
                    }

                    $current = now();
                    // if ($timer > $current) {
                    //     $skip = 1;
                    // }


                    // dd($allianceIDID, $corpIDID);

                    if ($input['timer_type'] == "Armor") {
                        $statusID = 5;
                    }

                    if ($input['timer_type'] == "Hull") {
                        $statusID = 13;
                    }
                    if ($input['timer_type'] == "Anchoring") {
                        $statusID = 14;
                    }

                    if ($input['timer_type'] == "Unanchoring") {
                        $statusID = 17;
                    }


                    $check = Station::where('rc_id', $input['id'])->first();

                    if (!$check) {

                        $check = Station::where('name', $input['structure_name'])->first();
                        if ($check) {
                            Station::where('name', $input['structure_name'])->update(['rc_id' => $input['id'], 'show_on_rc' => 1, 'show_on_coord' => 0]);
                        } else {
                            $newStation = 1;
                        }
                    }
                    if ($newStation == 0) {
                        $station = Station::where('rc_id', $input['id'])->first();
                        if ($timer != $station->out_time) {
                            $station->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1, 'show_on_coord' => 0, 'name' => $input['structure_name']]);
                        } else {

                            $station->update(['show_on_rc' => 1, 'station_status_id' => $statusID, 'show_on_coord' => 0, 'name' => $input['structure_name']]);
                        }

                        // dd($check->id);
                    } else {

                        $rcid = $input['id'];
                        // dd($input);
                        $reconpull = $this->reconPullbyname($stationName, $rcid);
                        // dd($reconpull, $stationName);


                        if ($reconpull == false) {
                            // dd("yoyo");
                            $id = Station::where('added_from_recon',  0)->max('id');
                            if ($id == null) {
                                $id = 1;
                            } else {
                                $id = $id + 1;
                            }



                            $new = Station::Create(['name' => $input['structure_name'], 'system_id' => $input['solar_system']['solar_system_id'], 'alliance_id' => $allianceIDID, 'corp_id' => $corpIDID, 'item_id' => $input['structure_type']['type_id'], 'station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1, 'rc_id' => $input['id'], 'show_on_coord' => 0]);
                            if ($allianceIDID == 0) {
                                $new->update(['id' => $id, 'text' => $input['owning_corp_ticker']]);
                            } else {
                                $new->update(['id' => $id]);
                            }
                        } else {

                            $check = Station::where('id', $reconpull)->first();
                            $check->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1, 'show_on_coord' => 0]);

                            if ($check) {
                                // echo "old";
                                // $checkid = $check["id"];
                                // dd($check->id);
                            }
                        }
                    }
                }
            }

            $logStation = Station::where('rc_id', $input['id'])->first();
            $log = Logging::create(['station_id' => $logStation->id, 'logging_type_id' => 18, 'text' => "Added from RC2"]);
            Helper::stationlogs($log->id);
        }

        Station::where('show_on_rc', 5)->update(['rc_id' => null, 'station_status_id' => 18]);
        $flag = collect([
            'flag' => 2
        ]);
        broadcast(new RcSheetUpdate($flag));
        // dd('yo');
    }


    public function invalidStructure($item_id)
    {
        $items = [
            2774,
            32458,
            2775,
            2776,
            2777,
            2778,
            2779,
            2780,
            2781,
            2782,
            2783,
            2784,
            2785,
            3059,
            3495,
            3499,
            3591,
            4361,
            12235,
            12236,
            16213,
            16214,
            17777,
            17778,
            17779,
            2233,
            35837,
            16286,
            32226,
        ];

        if (in_array($item_id, $items)) {
            return false;
        } else {
            return true;
        }
    }







    // $inputs = $request->all();
    // foreach ($inputs as $input) {
    //     $stationName = $input['name'];
    //     $timer = Helper::fixtime($input['Expires']);
    //     $allianceID = Alliance::where('ticker', $input['Ticker'])->first();
    //     if (!$allianceID) {
    //         $allianceIDID = 0;
    //     } else {
    //         $allianceIDID = $allianceID->id;
    //     }
    //     $itemID = Item::where('item_name', $input['Type'])->first();
    //     $systemID = System::where('system_name', $input['System'])->first();

    //     if ($input['Timer'] == "Armor") {
    //         $statusID = 8;
    //     }

    //     if ($input['Timer'] == "Hull") {
    //         $statusID = 9;
    //     }
    //     if ($input['Timer'] == "Anchoring") {
    //         $statusID = 14;
    //     }


    //     $check = Station::where(['name' => $input['name'], 'system_id' => $systemID->id, 'alliance_id' => $allianceIDID])->first();
    //     if ($check) {
    //         // $checkid = $check["id"];
    //         $check->update(['station_status_id' => $statusID, 'out_time' => $timer]);
    //         // dd($check->id);
    //     } else {



    //         $reconpull = $this->reconPullbyname($stationName);
    //         // dd($reconpull);


    //         if (!$reconpull) {
    //             // dd("yoyo");
    //             $id = Station::where('id', '<', 10000000000)->max('id');
    //             if ($id == null) {
    //                 $id = 1;
    //             } else {
    //                 $id = $id + 1;
    //             }



    //             $new = Station::Create(['name' => $input['name'], 'system_id' => $systemID->id, 'alliance_id' => $allianceIDID, 'item_id' => $itemID->id, 'station_status_id' => $statusID, 'out_time' => $timer]);
    //             if ($allianceIDID == 0) {
    //                 $new->update(['id' => $id, 'text' => $input['Ticker']]);
    //             } else {
    //                 $new->update(['id' => $id]);
    //             }
    //         }
    //     }
    //     // dd('yo');
    // }


    public static function reconPullbyname($stationName, $rcid)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $url = "https://recon.gnf.lt/api/structure/" . $stationName;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        // dd($stationdata);
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
                    'show_on_rc' => 1,
                    'rc_id' => $rcid,
                    'added_from_recon' => 1


                ]);
                // dd($stationdata);
                if ($stationdata['str_has_no_fitting'] != null) {
                    if ($stationdata['str_has_no_fitting'] != "No Fitting") {
                        $items = Utils::jsonDecode($stationdata['str_fitting'], true);

                        foreach ($items as $item) {
                            StationItems::where('id', $item['type_id'])->get()->count();
                            if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                            }
                            StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                        };
                    }
                }

                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $item = Item::where('id', $stationdata['str_type_id'])->first();
                $system = System::where('id', $stationdata['str_system_id'])->first();

                return $stationdata['str_structure_id'];
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
