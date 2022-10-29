<?php

use App\Events\StationDataSet;
use App\Events\StationInfoSet;
use App\Events\StationNotificationDelete;
use App\Events\StationNotificationNew;
use App\Events\StationNotificationUpdate;
use App\Events\TowerChanged;
use App\Events\TowerDelete;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Notification;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\StationRecords;
use App\Models\System;
use App\Models\Temp_notifcation;
use App\Models\Tower;
use App\Models\TowerRecord;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Yaml\Yaml;

if (!function_exists('reconRegionPull')) {
    function reconRegionPull($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structures/hostile/region/' . $id;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => e     nv('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);

        return $data;
    }
}

if (!function_exists('reconRegionPullIdCheck')) {
    function reconRegionPullIdCheck($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $i = 0;

        $url = 'https://recon.gnf.lt/api/structure/' . $id;
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
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
            $oldStation = Station::where('id', $id)->first();

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
                // echo '<pre>';
                // print_r($stationdata);
                // echo '</pre>';
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

if (!function_exists('reconPull')) {
    function reconPull($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structure/' . $id;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data == 'Error, Structure Not Found') {
            authcheck();
            $stationdata = authpull('station', $id);

            return $stationdata;
        } else {
            return $data;
        }
    }
}
if (!function_exists('dubp')) {
    function dubp()
    {
        $dups = Station::groupBy('name')->select('name', DB::raw('count(*) as total'))->get();
        foreach ($dups as $dup) {
            if ($dup->total > 1) {
                $stations = Station::where('name', $dup->name)->orderByDesc('id')->get();

                $stations[0]->update([

                    'user_id' => $stations[1]['user_id'] ?? null,
                    'text' => $stations[1]['text'] ?? null,
                    'notes' => $stations[1]['notes'] ?? null,
                    'attack_notes' => $stations[1]['attack_notes'] ?? null,
                    'attack_adash_link' => $stations[1]['attack_adash_link'] ?? null,
                    'station_status_id' => $stations[1]['station_status_id'] ?? null,
                    'gunner_id' => $stations[1]['gunner_id'] ?? null,
                    'out_time' => $stations[1]['out_time'] ?? null,
                    'repair_time' => $stations[1]['repair_time'] ?? null,
                    'ammo_request_id' => $stations[1]['ammo_request_id'] ?? null,
                    'status_update' => $stations[1]['status_update'] ?? null,
                    'timestamp' => $stations[1]['timestamp'] ?? null,
                    'show_on_main' => $stations[1]['show_on_main'] ?? null,
                    'show_on_chill' => $stations[1]['show_on_chill'] ?? null,
                    'show_welp' => $stations[1]['show_welp'] ?? null,
                    'show_on_rc' => $stations[1]['show_on_rc'] ?? null,
                    'show_on_rc_move' => $stations[1]['show_on_rc_move'] ?? null,
                    'show_on_coord' => $stations[1]['show_on_coord'] ?? null,
                    'added_by_user_id' => $stations[1]['added_by_user_id'] ?? null,
                    'timer_image_link' => $stations[1]['timer_image_link'] ?? null,
                    'rc_id' => $stations[1]['rc_id'] ?? null,
                    'rc_fc_id' => $stations[1]['rc_fc_id'] ?? null,
                    'rc_gsol_id' => $stations[1]['rc_gsol_id'] ?? null,
                    'rc_recon_id' => $stations[1]['rc_recon_id'] ?? null,
                    'rc_alliance_id' => $stations[1]['rc_alliance_id'] ?? null,
                    'rc_corp_id' => $stations[1]['rc_corp_id'] ?? null,

                ]);
            }
        }
    }
}
if (!function_exists('reconUpdate')) {
    function reconUpdate()
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $stations = Station::where('added_from_recon', 1)->where('import_flag', 0)->get();
        foreach ($stations as $station) {
            $url = 'https://recon.gnf.lt/api/structure/' . $station->id;
            $client = new GuzzleHttpClient();
            $headers = [
                // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
                'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
                // 'token' =>  env('RECON_TOKEN', "DANCE")
                'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

            ];
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false,
            ]);

            $stationdata = Utils::jsonDecode($response->getBody(), true);
            if ($stationdata == 'Error, Structure Not Found') {
                $s = Station::find($station->id)->get();
                foreach ($s as $s) {
                    $s->delete();
                }
                $s = StationItemJoin::where('station_id', $station->id)->get();
                foreach ($s as $s) {
                    $s->delete();
                }
            } else {
                $s = StationItemJoin::where('station_id', $station->id)->get();
                foreach ($s as $s) {
                    $s->delete();
                }
                $oldupdate = $station->r_updated_at;
                if ($oldupdate != $stationdata['updated_at']) {
                    $s = System::where('id', $station->system_id)->get();
                    foreach ($s as $s) {
                        $s->update(['task_flag' => 0]);
                    }
                }
                $oldStation = Station::where('id', $station->id)->first();
                $core = 0;
                $standing = 0;
                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
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

                $s = Station::where('id', $station->id)->first();

                $s->update([
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
                    'added_from_recon' => 1,
                ]);

                $stationNew = Station::where('id', $station->id)->first();

                if ($station->station_status_id == 7) {
                    $s = Station::where('id', $station->id)->get();
                    foreach ($s as $s) {
                        $s->update(['station_status_id' => 16]);
                    }
                }

                if ($stationdata['str_has_no_fitting'] != null) {
                    if ($stationdata['str_has_no_fitting'] != 'No Fitting') {
                        $s = StationItemJoin::where('station_id', $station->id)->get();
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
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $station->id]);
                            }
                        }
                    }
                }
            }
        }
        $stations = Station::where('added_from_recon', 0)->get();
        foreach ($stations as $station) {
            $url = 'https://recon.gnf.lt/api/structure/' . $station->name;
            $client = new GuzzleHttpClient();
            $headers = [
                // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
                'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
                // 'token' =>  env('RECON_TOKEN', "DANCE")
                'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

            ];
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false,
            ]);

            $stationdata = Utils::jsonDecode($response->getBody(), true);
            if ($stationdata == 'Error, Structure Not Found') {
            } else {
                $oldupdate = $station->r_updated_at;
                if ($oldupdate != $stationdata['updated_at']) {
                    $s = System::where('id', $station->system_id)->get();
                    foreach ($s as $s) {
                        $s->update(['task_flag' => 0]);
                    }
                }
                $core = 0;
                if ($stationdata['str_cored'] == 'Yes') {
                    $core = 1;
                }

                $s = Station::where('name', $station->name)->first();

                $s->update([
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
                    'r_cored' => $core,
                    'added_from_recon' => 1,
                ]);

                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    }
                }
            }
        }

        $flag = [
            'message' => 'yoyo',
        ];
        broadcast(new StationDataSet($flag));
        broadcast(new StationInfoSet($flag));
    }
}

if (!function_exists('test')) {
    function test($var, $show)
    {
        $time = $var['timestamp'];
        $time = fixtime($time);
        $data = [];
        $text = $var['text'];
        $text = str_replace('solarSystemID', 'system_id', $text);
        $text = str_replace('structureTypeID', 'item_id', $text);
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
            $station_id = [
                'station_id' => $text['structureID'],
            ];
        } elseif ($var['type'] == 'AllAnchoringMsg') {
            $towernumber = Tower::max('id');
            if ($towernumber == null || $towernumber == 0) {
                $towernumber = 1;
            }
            $moon_id = [
                'moon_id' => $text['moonID'],
            ];
        } elseif ($var['type'] == 'StructureDestroyed') {
            $s = Station::where('id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->update(['station_status_id' => 7, 'out_time' => null]);
            }
            $s = StationNotificationShield::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationNotificationArmor::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationItemJoin::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
        }

        // dd($data);

        if ($var['type'] == 'AllAnchoringMsg') {
            // if ($var['notification_id'] > $towernumber) {

            $time = $var['timestamp'];
            $time = fixtime($time);
            $data = [];
            $text = $var['text'];
            $text = str_replace('solarSystemID', 'system_id', $text);
            $text = str_replace('structureTypeID', 'item_id', $text);
            $text = Yaml::parse($text);

            $data = [
                'id' => $var['notification_id'],
                'alliance_id' => $text['allianceID'],
                'item_id' => $text['typeID'],
                'timestamp' => $time,
                'tower_status_id' => 1,
                'user_id' => null,

            ];
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
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        $core = 0;
                        if ($stationdata['str_cored'] == 'Yes') {
                            $core = 1;
                        }

                        $standing = 0;
                        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
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
                            'r_cored' => $core,
                            'status_update' => $current,
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
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
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
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
                        'show_on_main' => setShowMain($station, $show),
                        'show_on_chill' => setShowChill($station, $show),
                        'show_on_welp' => setShowWelp($station, $show),
                    ]);
                }

                $data = [
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                ];
                StationNotification::updateOrCreate($station_id, $data);
            }

            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostShields') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs = (int) ($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date('Y-m-d H:i:s', $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {
                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        $core = 0;
                        if ($stationdata['str_cored'] == 'Yes') {
                            $core = 1;
                        }

                        $standing = 0;
                        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
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
                            'r_cored' => $core,
                            'status_update' => $current,
                            'out_time' => $outTime,
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
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
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
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
                        'show_on_main' => setShowMain($station, $show),
                        'show_on_chill' => setShowChill($station, $show),
                        'show_on_welp' => setShowWelp($station, $show),
                    ]);
                }

                $data = [
                    'id' => $var['notification_id'],
                    'timestamp' => $time,

                ];
                StationNotificationShield::updateOrCreate($station_id, $data);
            }
            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostArmor') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs = (int) ($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date('Y-m-d H:i:s', $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {
                $station = Station::where('id', $text['structureID'])->first();
                // echo $stationcheck;
                if ($station == null) {
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
                        $core = 0;
                        if ($stationdata['str_cored'] == 'Yes') {
                            $core = 1;
                        }

                        $standing = 0;
                        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
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
                            'r_cored' => $core,
                            'status_update' => $current,
                            'out_time' => $outTime,
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
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
                            'show_on_main' => setShowMainNew($show),
                            'show_on_chill' => setShowChillNew($show),
                            'show_on_welp' => setShowWelpNew($show),
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
                        'show_on_main' => setShowMain($station, $show),
                        'show_on_chill' => setShowChill($station, $show),
                        'show_on_welp' => setShowWelp($station, $show),
                    ]);

                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,

                    ];
                    StationNotificationArmor::updateOrCreate($station_id, $data);
                }
            }
            $message = StationRecords::where('id', $text['structureID'])->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        }
    }
}

if (!function_exists('notificationUpdate')) {
    function notificationUpdate($data)
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
            $n = Notification::where('status_id', 2)
                ->where('timestamp', '<=', $now)->get();

            foreach ($n as $n) {
                $n->update(['status_id' => 10]);
            }
            $flag = 1;
            $check = null;
        }

        $check = Notification::where('status_id', 4)
            ->where('timestamp', '<=', $now)
            ->count();

        if ($check > 0) {
            $n = Notification::where('status_id', 4)
                ->where('timestamp', '<=', $now)->get();
            foreach ($n as $n) {
                $n->update(['status_id' => 10]);
            }
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

                    $time = fixtime($time);
                    $result = [];
                    $data = [];
                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace('solarSystemID', 'system_id', $text);
                    $text = str_replace('structureTypeID', 'item_id', $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        $item = $keys;
                        array_pop($keys);
                        unset($item[0]);
                        $item = array_map('trim', $item);
                        $item[1] = (int) $item[1];
                        $item = array_values($item);
                        $result[$keys[0]] = $item[0];
                    }

                    $si_id = $result['system_id'] . $result['item_id'];
                    $check_si_id = $si_id;
                    $check_si_id = (int) $check_si_id;
                    $si_id = [
                        'si_id' => $si_id = (int) $si_id,
                    ];

                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 1,
                        'status_id' => 1,
                        'user_id' => null,

                    ];
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
                    $time = fixtime($time);
                    $result = [];
                    $data = [];

                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace('campaignEventType', 'event_type_id', $text);
                    $text = str_replace('solarSystemID', 'system_id', $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        if ($keys[0] !== 'decloakTime') {
                            $item = $keys;
                            array_pop($keys);
                            unset($item[0]);
                            $item = array_map('trim', $item);
                            $item[1] = (int) $item[1];
                            $item = array_values($item);
                            $result[$keys[0]] = $item[0];
                        }
                    }

                    $es_id = $result['event_type_id'] . $result['system_id'];
                    $check_es_id = $es_id;
                    $check_es_id = (int) $check_es_id;
                    $es_id = [
                        'es_id' => $es_id = (int) $es_id,
                    ];
                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 2,
                        'status' => 0,
                    ];

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
            $si_id = (int) $si_id;
            $check = Notification::where('si_id', $si_id)->get();
            if ($check->count() == 1) {
                $check = $check->toArray();

                if ($tempnote->id > $check[0]['id']) {
                    $n = Notification::where('si_id', $si_id)
                        ->where('item_id', $stype)->get();

                    foreach ($n as $n) {
                        $n->update([
                            'status_id' => 2,
                            'user_id' => null,
                        ]);
                    }
                }
                $t = Temp_notifcation::where('id', $tempnote->id)->get();
                foreach ($t as $t) {
                    $t->update(['status' => 1]);
                }
            } else {
                $t = Temp_notifcation::where('id', $tempnote->id)->get();
                foreach ($t as $t) {
                    $t->update(['status' => 1]);
                }
            }
        }

        return $request = [
            'stationflag' => $stationflag,
            'towerflag' => $towerflag,
            'notificationflag' => $flag,
        ];
    }
}
if (!function_exists('stationNotificationCheck')) {
    function stationNotificationCheck()
    {
        $now = now();
        $now5min = now()->subMinutes(5);
        $now10min = now()->subMinutes(10);
        $now20min = now()->subMinutes(20);
        $now30min = now()->subMinutes(30);
        $now1hour = now()->subHours(1);
        $now5hour = now()->subHours(5); //if less than
        $soon24hour = now()->addDay();

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 1)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //New
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 2)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //On They way
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 3)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Gunning
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 4)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Saved
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('out_time', '<=', $now)->where('station_status_id', 5)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Upcoming
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 6, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('out_time', '<=', $now)->where('station_status_id', 13)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Upcoming
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 6, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('out_time', '<=', $now)->where('station_status_id', 14)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Upcoming
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 15, 'status_update' => now(), 'out_time' => null, 'timestamp' => now()]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationUpdate($flag));
        }

        $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 6)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Out
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('out_time', '<', $now10min)->where('station_status_id', 15)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Out
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 7)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Destoryed
        foreach ($checks as $check) {
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $check->id,
            ]);
            broadcast(new StationNotificationDelete($flag));
            $s = StationItemJoin::where('station_id', $check->id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationNotificationArmor::where('station_id', $check->id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationNotificationShield::where('station_id', $check->id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationNotification::where('station_id', $check->id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $check->delete();
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 8)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Reffed - Shield
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 5, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'status_update' => now(), 'gunner_id' => null, 'attack_notes' => null, 'attack_adash_link' => null]);
            $stationID = $check->id;
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
        }

        $checks = Station::where('status_update', '<', $now10min)->where('station_status_id', 9)->where('show_on_rc', 0)->where('show_on_rc_move', 0)->where('show_on_coord', 0)->where('show_on_chill', 0)->where('show_on_welp', 0)->get(); //Reffed - Armor
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 13, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'status_update' => now(), 'gunner_id' => null, 'attack_notes' => null, 'attack_adash_link' => null]);
            $message = StationRecords::where('id', $check->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
        }
    }
}
if (!function_exists('towerUpdate')) {
    function towerUpdate()
    {
        $now10min = now()->modify(' -10 minutes');
        $now10min = now()->subMinutes(10);
        $towers = Tower::where('tower_status_id', 6)->where('updated_at', '<', $now10min)->get();
        foreach ($towers as $tower) {
            $id = $tower->id;
            $flag = null;
            $flag = collect([
                'id' => $id,
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
}

if (!function_exists('setShowMain')) {
    function setShowMain($pull, $show)
    {
        $showMain = $pull->show_on_main;

        if ($show == 1) {
            return 1;
        } else {
            return $showMain;
        }
    }
}
if (!function_exists('setShowChill')) {
    function setShowChill($pull, $show)
    {
        $showChill = $pull->show_on_chill;

        if ($show == 2) {
            return 1;
        } else {
            return $showChill;
        }
    }
}
if (!function_exists('setShowWelp')) {
    function setShowWelp($pull, $show)
    {
        $showWelp = $pull->show_on_welp;

        if ($show == 3) {
            return 1;
        } else {
            return $showWelp;
        }
    }
}
if (!function_exists('setShowMainNew')) {
    function setShowMainNew($show)
    {
        if ($show == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
if (!function_exists('setShowChillNew')) {
    function setShowChillNew($show)
    {
        if ($show == 2) {
            return 1;
        } else {
            return 0;
        }
    }
}
if (!function_exists('setShowWelpNew')) {
    function setShowWelpNew($show)
    {
        if ($show == 3) {
            return 1;
        } else {
            return 0;
        }
    }
}


if (!function_exists('notificationSolo')) {
    function notificationSolo($id)
    {
        $data =  Notification::where('id', $id)
            ->with([
                'system:id,region_id,constellation_id,system_name,adm,tidi',
                'system.region:id,region_name',
                'system.constellation:id,constellation_name',
                'item',
                'notification_type:id,name',
                'status:id,name',
                'updatedBy:id,name'
            ])
            ->first();

        return ["notification" => $data];
    }
}
