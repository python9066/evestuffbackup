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
use App\Models\System;
use Illuminate\Support\Facades\Auth;

class RCSheet extends Controller
{
    public function RCInput(Request $request)
    {
        Station::where('show_on_rc', 1)->update(['show_on_rc' => 5]);
        // $inputs = $request->all();
        // foreach ($inputs as $input) {

        //     dd($input['created_by'], $input);
        // }
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            // echo  $input['structure_name'];
            if ($input['is_hostile'] != null) {
                if (
                    $input['structure_type']['type_id'] != 2774
                    || $input['structure_type']['type_id'] != 2775
                    || $input['structure_type']['type_id'] != 2776
                    || $input['structure_type']['type_id'] != 2777
                    || $input['structure_type']['type_id'] != 2778
                    || $input['structure_type']['type_id'] != 2779
                    || $input['structure_type']['type_id'] != 2780
                    || $input['structure_type']['type_id'] != 2781
                    || $input['structure_type']['type_id'] != 2782
                    || $input['structure_type']['type_id'] != 2783
                    || $input['structure_type']['type_id'] != 2784
                    || $input['structure_type']['type_id'] != 2785
                    || $input['structure_type']['type_id'] != 3059
                    || $input['structure_type']['type_id'] != 3495
                    || $input['structure_type']['type_id'] != 3499
                    || $input['structure_type']['type_id'] != 3591
                    || $input['structure_type']['type_id'] != 4361
                    || $input['structure_type']['type_id'] != 12235
                    || $input['structure_type']['type_id'] != 12236
                    || $input['structure_type']['type_id'] != 16213
                    || $input['structure_type']['type_id'] != 16214
                    || $input['structure_type']['type_id'] != 17777
                    || $input['structure_type']['type_id'] != 17778
                    || $input['structure_type']['type_id'] != 17779
                    || $input['structure_type']['type_id'] != 2233
                    || $input['structure_type']['type_id'] != 35837
                    || $input['structure_type']['type_id'] != 16286
                    || $input['structure_type']['type_id'] != 32226
                    || $input['structure_type']['type_id'] != 32458


                ) {
                    // dd($input);
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


                    // dd($allianceIDID, $corpIDID);

                    if ($input['timer_type'] == "Armor") {
                        $statusID = 2;
                    }

                    if ($input['timer_type'] == "Hull") {
                        $statusID = 1;
                    }
                    if ($input['timer_type'] == "Anchoring") {
                        $statusID = 3;
                    }

                    if ($input['timer_type'] == "Unanchoring") {
                        $statusID = 4;
                    }

                    $check = null;
                    // $check = Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->get();
                    $check = Station::where('rc_id', $input['id'])->get();
                    $count = $check->count();

                    if ($count > 0) {
                    } else {
                        $check = Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->get();
                        $count = $check->count();
                    }

                    if ($count > 0) {
                        // dd($check[0]['station_status_id']);
                        // $checkid = $check["id"];
                        if ($check[0]['station_status_id'] > 4) {
                            $statusID = 5;
                        }


                        // Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1]);

                        if ($check[0]['rc_id'] != null) {
                            if ($statusID == 5) {

                                // Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->update(['station_status_id' => $statusID, 'show_on_rc' => 2]);
                                Station::where('rc_id', $input['id'])->update(['station_status_id' => $statusID, 'show_on_rc' => 2]);
                            } else {
                                Station::where('rc_id', $input['id'])->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1]);;
                            }
                        } else {

                            if ($statusID == 5) {

                                // Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->update(['station_status_id' => $statusID, 'show_on_rc' => 2]);
                                Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->update(['station_status_id' => $statusID, 'show_on_rc' => 2]);
                            } else {
                                Station::where('name', $input['structure_name'])->where('system_id', $input['solar_system']['solar_system_id'])->where('alliance_id', $allianceIDID)->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1]);;
                            }
                        }

                        // dd($check->id);
                    } else {

                        $rcid = $input['id'];
                        // dd($input);
                        $reconpull = $this->reconPullbyname($stationName, $rcid);
                        // dd($reconpull, $stationName);


                        if ($reconpull == false) {
                            // dd("yoyo");
                            $id = Station::where('id', '<', 10000000000)->max('id');
                            if ($id == null) {
                                $id = 1;
                            } else {
                                $id = $id + 1;
                            }



                            $new = Station::Create(['name' => $input['structure_name'], 'system_id' => $input['solar_system']['solar_system_id'], 'alliance_id' => $allianceIDID, 'corp_id' => $corpIDID, 'item_id' => $input['structure_type']['type_id'], 'station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1, 'rc_id' => $input['id']]);
                            if ($allianceIDID == 0) {
                                $new->update(['id' => $id, 'text' => $input['owning_corp_ticker']]);
                            } else {
                                $new->update(['id' => $id]);
                            }
                        } else {

                            $check = Station::where('id', $reconpull)->first();

                            if ($check) {
                                // echo "old";
                                // $checkid = $check["id"];
                                $check->update(['station_status_id' => $statusID, 'out_time' => $timer, 'show_on_rc' => 1]);
                                // dd($check->id);
                            }
                        }
                    }
                }
            }
        }


        $flag = collect([
            'flag' => 2
        ]);
        broadcast(new RcSheetUpdate($flag));
        // dd('yo');
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
