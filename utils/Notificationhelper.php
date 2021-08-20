<?php

namespace utils\Notificationhelper;

use App\Events\StationDataSet;
use App\Events\StationInfoSet;
use App\Events\StationNotificationDelete;
use App\Events\StationNotificationNew;
use App\Events\StationNotificationUpdate;
use App\Events\TowerChanged;
use App\Events\TowerDelete;
use App\Models\Notification;
use App\Models\Temp_notifcation;
use utils\Helper\Helper;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\StationRecords;
use App\Models\System;
use App\Models\Tower;
use App\Models\TowerRecord;
use GuzzleHttp\Utils;
use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Client as GuzzleHttpClient;


class Notifications
{


    public static function reconRegionPull($id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $url = "https://recon.gnf.lt/api/structures/hostile/region/" . $id;



        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => e     nv('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        return $data;
    }



    public static function reconRegionPullIdCheck($id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);



        $url = "https://recon.gnf.lt/api/structure/" . $id;
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
        if ($stationdata == "Error, Structure Not Found") {
            Station::find($id)->delete();
            StationItemJoin::where('station_id', $id)->delete();
        } else {
            StationItemJoin::where('station_id', $id)->delete();
            Station::updateOrCreate(['id' => $id], [
                'name' => $stationdata['str_name'],
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
                'r_cored' => $stationdata['str_cored'],
                'system_id' => $stationdata['str_system_id'],
                'item_id' => $stationdata['str_type_id'],

            ]);
            $checkflag = Station::find($id)->first();
            if ($checkflag->show_on_rc != 1 || $checkflag->show_on_rc_move != 1) {
                $checkflag->update(['show_on_coord' => 1]);
                if ($checkflag->station_status_id == 10) {
                    $checkflag->update(['station_status_id' => 1]);
                }
            }



            if ($stationdata['str_has_no_fitting'] != null) {
                if ($stationdata['str_has_no_fitting'] != 'No Fitting') {
                    StationItemJoin::where('station_id', $id)->delete();
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $id]);
                    };
                }
            }
        }
    }



    //////////////////
    public static function reconPull($id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $url = "https://recon.gnf.lt/api/structure/" . $id;

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
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data == "Error, Structure Not Found") {
            Helper::authcheck();
            $stationdata = Helper::authpull('station', $id);
            return $stationdata;
        } else {
            return $data;
        }
    }

    public static function reconUpdate()
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);

        $stations = Station::where('id', '>=', 1000000000);
        foreach ($stations as $station) {
            $url = "https://recon.gnf.lt/api/structure/" . $station->id;
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
            if ($stationdata == "Error, Structure Not Found") {
                Station::find($station->id)->delete();
                StationItemJoin::where('station_id', $station->id)->delete();
            } else {
                StationItemJoin::where('station_id', $station->id)->delete();
                $oldupdate = $station->r_updated_at;
                if ($oldupdate != $stationdata['updated_at']) {
                    System::where('id', $station->system_id)->update(['task_flag' => 0]);
                }
                Station::where('id', $station->id)->update([
                    'name' => $stationdata['str_name'],
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
                    'r_cored' => $stationdata['str_cored']
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    if ($stationdata['str_has_no_fitting'] != 'No Fitting') {
                        StationItemJoin::where('station_id', $station->id)->delete();
                        $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                        foreach ($items as $item) {
                            StationItems::where('id', $item['type_id'])->get()->count();
                            if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                            }
                            StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $station->id]);
                        };
                    }
                }
            }
        }
        $stations = Station::where('id', '<', 1000000000);
        foreach ($stations as $station) {
            $url = "https://recon.gnf.lt/api/structure/" . $station->name;
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
            if ($stationdata == "Error, Structure Not Found") {
            } else {
                StationItemJoin::where('station_id', $stationdata['str_structure_id'])->delete();
                $oldupdate = $station->r_updated_at;
                if ($oldupdate != $stationdata['updated_at']) {
                    System::where('id', $station->system_id)->update(['task_flag' => 0]);
                }
                Station::where('id', $station->name)->update([
                    'id' => $stationdata['str_structure_id'],
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
                    'r_cored' => $stationdata['str_cored']
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
            }
        }
        $flag = [
            'message' => 'yoyo'
        ];
        broadcast(new StationDataSet($flag));
        broadcast(new StationInfoSet($flag));
    }
    public static function test($var, $show)
    {



        $time = $var['timestamp'];
        $time = Helper::fixtime($time);
        $data = array();
        $text = $var['text'];
        $text = str_replace("solarSystemID", "system_id", $text);
        $text = str_replace("structureTypeID", "item_id", $text);
        $text = Yaml::parse($text);
        $current = now();





        if ($var['type'] == 'StructureUnderAttack' || $var['type'] == 'StructureLostShields' || $var['type'] == 'StructureLostArmor') {
            $stationnotenumber = StationNotification::where('station_id', $text['structureID'])->max('id');
            $stationshieldnumber = StationNotificationShield::where('station_id', $text['structureID'])->max('id');
            $stationarmornumber = StationNotificationArmor::where('station_id', $text['structureID'])->max('id');
            $maxNotificationID = max($stationnotenumber, $stationshieldnumber, $stationarmornumber);
            if ($maxNotificationID == null || $maxNotificationID == 0) {
                $maxNotificationID == 1;
            }
            $station_id = array(
                'station_id' => $text['structureID'],
            );
        } else if ($var['type'] == 'AllAnchoringMsg') {
            $towernumber = Tower::max('id');
            if ($towernumber == null || $towernumber == 0) {
                $towernumber = 1;
            }
            $moon_id = array(
                'moon_id' => $text['moonID']
            );
        } else if ($var['type'] == 'StructureDestroyed') {
            Station::where('id', $text['structureID'])->update(['station_status_id' => 7, 'out_time' => null]);
            StationNotificationShield::where('station_id', $text['structureID'])->delete();
            StationNotificationArmor::where('station_id', $text['structureID'])->delete();
            StationItemJoin::where('station_id', $text['structureID'])->delete();
        }



        // dd($data);

        if ($var['type'] == 'AllAnchoringMsg') {
            // if ($var['notification_id'] > $towernumber) {

            $time = $var['timestamp'];
            $time = Helper::fixtime($time);
            $data = array();
            $text = $var['text'];
            $text = str_replace("solarSystemID", "system_id", $text);
            $text = str_replace("structureTypeID", "item_id", $text);
            $text = Yaml::parse($text);

            $data = array(
                'id' => $var['notification_id'],
                'alliance_id' => $text['allianceID'],
                'item_id' => $text['typeID'],
                'timestamp' => $time,
                'tower_status_id' => 1,
                'user_id' => null,

            );
            Tower::updateOrCreate($moon_id, $data);
            // $check = Tower::where('moon_id', $moon_id)->first();
            // if ($check == null) {
            //     Tower::updateOrCreate($moon_id, $data);
            // } else {

            //     if ($var['notification_id'] > $towernumber) {

            //         Tower::updateOrCreate($moon_id, $data);
            //     }
            // }
            // }
        } elseif ($var['type'] == 'StructureUnderAttack') {



            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    $stationdata = Notifications::reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'system_id' => $stationdata['str_system_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 1,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            };
                        }
                    } else {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'corp_id' => $stationdata['owner_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 1,
                            'timestamp' => $time,
                            'status_update' => $current,
                            'out_time' => null,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                    }
                } else {
                    if ($station->station_status_id == 6 || $station->station_status_id == 10) {
                        $status = 1;
                    } else {
                        $status = $station->station_status_id;
                    }
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => $status,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'show_on_main' => Notifications::setShowMain($station, $show),
                        'show_on_chill' => Notifications::setShowChill($station, $show)
                    ]);
                }

                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time
                );
                StationNotification::updateOrCreate($station_id, $data);
            }

            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostShields') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs       = (int)($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date("Y-m-d H:i:s", $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    $stationdata = Notifications::reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'system_id' => $stationdata['str_system_id'],
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 8,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'out_time' =>  $outTime,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            };
                        }
                    } else {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'corp_id' => $stationdata['owner_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 8,
                            'timestamp' => $time,
                            'out_time' => $outTime,
                            'status_update' => $current,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                    }
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 8,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'out_time' => $outTime,
                        'show_on_main' => Notifications::setShowMain($station, $show),
                        'show_on_chill' => Notifications::setShowChill($station, $show)
                    ]);
                }

                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,

                );
                StationNotificationShield::updateOrCreate($station_id, $data);
            }
            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostArmor') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs       = (int)($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date("Y-m-d H:i:s", $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                // echo $stationcheck;
                if ($station == null) {
                    $stationdata = Notifications::reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'system_id' => $stationdata['str_system_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'user_id' => null,
                            'station_status_id' => 9,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'out_time' =>  $outTime,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            };
                        }
                    } else {

                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'text' => null,
                            'corp_id' => $stationdata['owner_id'],
                            'user_id' => null,
                            'station_status_id' => 9,
                            'timestamp' => $time,
                            'out_time' => $outTime,
                            'status_update' => $current,
                            'show_on_main' => Notifications::setShowMainNew($show),
                            'show_on_chill' => Notifications::setShowChillNew($show)
                        ]);
                    }
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 9,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'out_time' => $outTime,
                        'show_on_main' => Notifications::setShowMain($station, $show),
                        'show_on_chill' => Notifications::setShowChill($station, $show)
                    ]);

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,

                    );
                    StationNotificationArmor::updateOrCreate($station_id, $data);
                }
            }
            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        }
    }



    public static function update($data)
    {
        $current = now();
        $now = $current->modify('-10 minutes');
        $stationflag = 0;
        $towerflag = 0;
        $flag = 0;

        $check = Notification::where('status_id', 2)
            ->where('timestamp', '<=', $now)
            ->count();
        // dd($check);
        if ($check > 0) {

            Notification::where('status_id', 2)
                ->where('timestamp', '<=', $now)
                ->update(['status_id' => 10]);
            $flag = 1;
            $check = null;
        }

        $check = Notification::where('status_id', 4)
            ->where('timestamp', '<=', $now)
            ->count();

        if ($check > 0) {
            Notification::where('status_id', 4)
                ->where('timestamp', '<=', $now)
                ->update(['status_id' => 10]);
            $flag = 1;
            $check = null;
        }


        $notenumber = Notification::max('id');
        $tempnumber = Temp_notifcation::max('id');
        foreach ($data as $var) {

            //dwdwdw
            if ($var['type'] == 'EntosisCaptureStarted') {
                if ($var['notification_id'] > $notenumber) {



                    //dd($time2);
                    $time = $var['timestamp'];

                    $time = Helper::fixtime($time);
                    $result = array();
                    $data = array();
                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        $item = $keys;
                        array_pop($keys);
                        unset($item[0]);
                        $item = array_map('trim', $item);
                        $item[1] = (int)$item[1];
                        $item = array_values($item);
                        $result[$keys[0]] = $item[0];
                    };
                    $si_id = $result['system_id'] . $result['item_id'];
                    $check_si_id = $si_id;
                    $check_si_id = (int)$check_si_id;
                    $si_id = array(
                        'si_id' => $si_id = (int)$si_id
                    );

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 1,
                        'status_id' => 1,
                        'user_id' => null,

                    );
                    $data2 = array_merge($data, $result);
                    $check = Notification::where('si_id', $check_si_id)->first();
                    $count = Notification::where('si_id', $check_si_id)->get()->count();
                    if ($count == 0) {
                        Notification::updateOrCreate($si_id, $data2);
                        $flag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            Notification::updateOrCreate($si_id, $data2);
                            $flag = 1;
                        }
                    }
                }
            } elseif ($var['type'] == 'SovStructureReinforced') {
                if ($var['notification_id'] > $tempnumber) {
                    $time2 = $var['timestamp'];
                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $result = array();
                    $data = array();

                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace("campaignEventType", "event_type_id", $text);
                    $text = str_replace("solarSystemID", "system_id", $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        if ($keys[0] !== 'decloakTime') {
                            $item = $keys;
                            array_pop($keys);
                            unset($item[0]);
                            $item = array_map('trim', $item);
                            $item[1] = (int)$item[1];
                            $item = array_values($item);
                            $result[$keys[0]] = $item[0];
                        };
                    };
                    $es_id = $result['event_type_id'] . $result['system_id'];
                    $check_es_id = $es_id;
                    $check_es_id = (int)$check_es_id;
                    $es_id = array(
                        'es_id' => $es_id = (int)$es_id
                    );
                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 2,
                        'status' => 0,
                    );

                    // ($data2);
                    $data2 = array_merge($data, $result);
                    $check = Temp_notifcation::where('es_id', $check_es_id)->first();
                    $count = Temp_notifcation::where('es_id', $check_es_id)->get()->count();
                    if ($count == 0) {
                        Temp_notifcation::updateOrCreate($es_id, $data2);
                        $flag = 1;
                    } else {
                        if ($var['notification_id'] > $check->id) {

                            Temp_notifcation::updateOrCreate($es_id, $data2);
                            $flag = 1;
                        }
                    }
                }
            }
        }


        $tempnote = Temp_notifcation::where('status', 0)->get();
        foreach ($tempnote as $tempnote) {
            if ($tempnote->event_type_id == 1) {
                $stype = 32226;
            } else {
                $stype = 32458;
            }

            $si_id = $tempnote->system_id . $stype;
            $si_id = (int)$si_id;
            $check = Notification::where('si_id', $si_id)->get();
            if ($check->count() == 1) {

                $check = $check->toArray();

                if ($tempnote->id > $check[0]['id']) {

                    Notification::where('si_id', $si_id)
                        ->where('item_id', $stype)
                        ->update([
                            'status_id' => 2,
                            'user_id' => null,
                        ]);
                }
                Temp_notifcation::where('id', $tempnote->id)->update(['status' => 1]);
            } else {

                Temp_notifcation::where('id', $tempnote->id)->update(['status' => 1]);
            }
        }


        return $request = array(
            'stationflag' => $stationflag,
            'towerflag' => $towerflag,
            'notificationflag' => $flag,
        );
    }

    public static function stationNotificationCheck()
    {
        $now = now();
        $now5min = now()->modify(' -5 minutes');
        $now10min = now()->modify(' -10 minutes');
        $now20min = now()->modify(' -20 minutes');
        $now30min = now()->modify(' -30 minutes');
        $now1hour = now()->modify(' -1 hour');
        $now5hour = now()->modify(' -5 hours'); //if less than
        $soon24hour = now()->modify(' +24 hours');

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 1)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //New
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 2)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //On They way
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 3)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Gunning
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }


        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 4)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Saved
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('out_time', "<=", $now)->where('station_status_id', 5)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Upcoming
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 6, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('out_time', "<=", $now)->where('station_status_id', 13)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Upcoming
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 6, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('out_time', "<=", $now)->where('station_status_id', 14)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Upcoming
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 15, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 6)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Out
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('out_time', '<', $now10min)->where('station_status_id', 15)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Out
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 7)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Destoryed
        foreach ($checks as $check) {

            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id
            ]);
            broadcast(new StationNotificationDelete($flag));
            StationItemJoin::where('station_id', $check->id)->delete();
            StationNotificationArmor::where('station_id', $check->id)->delete();
            StationNotificationShield::where('station_id', $check->id)->delete();
            StationNotification::where('station_id', $check->id)->delete();
            $check->delete();
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 8)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Reffed - Shield
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 5, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'status_update' => now(), 'gunner_id' => null, 'attack_notes' => null, 'attack_adash_link' => null]);
            $stationID = $check->id;
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationNew($flag));
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 9)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->get(); //Reffed - Armor
        foreach ($checks as $check) {

            $check->update(['station_status_id' => 13, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'status_update' => now(), 'gunner_id' => null, 'attack_notes' => null, 'attack_adash_link' => null]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message
            ]);
            broadcast(new StationNotificationNew($flag));
        }
    }

    public static function towerUpdate()
    {
        $now10min = now()->modify(' -10 minutes');
        $towers = Tower::where('tower_status_id', 6)->where('updated_at', '<', $now10min)->get();
        foreach ($towers as $tower) {
            $id = $tower->id;
            $flag = null;
            $flag = collect([
                'id' => $id
            ]);
            broadcast(new TowerDelete($flag));
            $tower->delete();
        }

        $towers = Tower::where('tower_status_id', 3)->where('out_time', '>', now())->get();
        foreach ($towers as $tower) {

            $tower->update(['tower_status_id' => 4, 'out_time' => null]);
            $message = TowerRecord::where('id', $tower->id)->first();
            if ($message->status_id != 10) {
                $flag = collect([
                    'message' => $message,
                ]);
                broadcast(new TowerChanged($flag));
            }
        }
        $towers = Tower::where('tower_status_id', 5)->where('out_time', '<', now())->get();
        foreach ($towers as $tower) {
            if ($tower->out_time != null) {
                $tower->update(['tower_status_id' => 8]);
                $message = TowerRecord::where('id', $tower->id)->first();
                if ($message->status_id != 10) {
                    $flag = collect([
                        'message' => $message,
                    ]);
                    broadcast(new TowerChanged($flag));
                }
            }
        }
    }


    public static function setShowMain($pull, $show)
    {

        $showMain = $pull->show_on_main;

        if ($show == 1) {
            return 1;
        } else {
            return $showMain;
        }
    }

    public static function setShowChill($pull, $show)
    {

        $showChill = $pull->show_on_chill;

        if ($show == 2) {
            return 1;
        } else {
            return $showChill;
        }
    }

    public static function setShowMainNew($show)
    {
        if ($show == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function setShowChillNew($show)
    {
        if ($show == 2) {
            return 1;
        } else {
            return 0;
        }
    }
}
