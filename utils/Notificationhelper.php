<?php

namespace utils\Notificationhelper;

use App\Events\StationNew;
use App\Events\StationNotificationDelete;
use App\Models\Notification;
use App\Models\Temp_notifcation;
use utils\Helper\Helper;
use App\Models\Station;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\StationRecords;
use App\Models\Tower;
use GuzzleHttp\Utils;
use Symfony\Component\Yaml\Yaml;


class Notifications
{
    public static function test($var)
    {


        $current = now();
        $now = $current->modify('-10 minutes');

        // $stationCheck = Station::where('station_status_id', '>', 3)
        //     ->where('timestamp', '<=', $now)
        //     ->get()
        //     ->count();
        // if ($stationCheck > 0) {

        //     Station::where('station_status_id', '>', 3)
        //         ->where('timestamp', '<=', $now)
        //         ->update(['station_status_id' => 10]);
        // }
        $time = $var['timestamp'];
        $time = Helper::fixtime($time);
        $data = array();
        $text = $var['text'];
        $text = str_replace("solarSystemID", "system_id", $text);
        $text = str_replace("structureTypeID", "item_id", $text);
        $text = Yaml::parse($text);





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
            Station::where('id', $text['structureID'])->delete();
            StationNotificationShield::where('station_id', $text['structureID'])->delete();
            StationNotificationArmor::where('station_id', $text['structureID'])->delete();
        }



        // dd($data);

        if ($var['type'] == 'AllAnchoringMsg') {
            if ($var['notification_id'] > $towernumber) {

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

                $check = Tower::where('moon_id', $moon_id)->first();
                if ($check->count() == 0) {
                    Tower::updateOrCreate($moon_id, $data);
                } else {

                    if ($var['notification_id'] > $check->id) {

                        Tower::updateOrCreate($moon_id, $data);
                    }
                }
            }
        } elseif ($var['type'] == 'StructureUnderAttack') {



            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    Helper::authcheck();
                    $stationdata = Helper::authpull('station', $text['structureID']);

                    Station::Create([
                        'id' => $text['structureID'],
                        'name' => $stationdata['name'],
                        'system_id' => $stationdata['solar_system_id'],
                        'item_id' => $stationdata['type_id'],
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 1,
                        'timestamp' => $time,
                    ]);
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 1,
                        'timestamp' => $time
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
            broadcast(new StationNew($flag))->toOthers();
        } elseif ($var['type'] == 'StructureLostShields') {

            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    Helper::authcheck();
                    $stationdata = Helper::authpull('station', $text['structureID']);

                    Station::Create([
                        'id' => $text['structureID'],
                        'name' => $stationdata['name'],
                        'system_id' => $stationdata['solar_system_id'],
                        'item_id' => $stationdata['type_id'],
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 8,
                        'timestamp' => $time
                    ]);
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 8,
                        'timestamp' => $time
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
            broadcast(new StationNew($flag))->toOthers();
        } elseif ($var['type'] == 'StructureLostArmor') {
            if ($var['notification_id'] > $maxNotificationID) {

                $station = Station::where('id', $text['structureID'])->first();
                // echo $stationcheck;
                if ($station == null) {
                    Helper::authcheck();
                    $stationdata = Helper::authpull('station', $text['structureID']);

                    Station::Create([
                        'id' => $text['structureID'],
                        'name' => $stationdata['name'],
                        'system_id' => $stationdata['solar_system_id'],
                        'item_id' => $stationdata['type_id'],
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 9,
                        'timestamp' => $time
                    ]);
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 9,
                        'timestamp' => $time
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
            broadcast(new StationNew($flag))->toOthers();
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
        $now10min = now()->modify(' -10 minutes');
        $now30min = now()->modify(' -30 minutes');
        $now5hour = now()->modify(' -5 hours'); //if less than

        $checks = Station::where('updated_at', '<', $now5hour)->where('station_status_id', 1)->get();
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $stationID
            ]);
            broadcast(new StationNotificationDelete($flag))->toOthers();
        }

        $checks = Station::where('updated_at', '<', $now30min)->where('station_status_id', 7)->get();
        foreach ($checks as $check) {
            $check->update(['station_status_id' => 10]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $stationID
            ]);
            broadcast(new StationNotificationDelete($flag))->toOthers();
        }

        $checks = Station::where('updated_at', '<', $now30min)->where('station_status_id', 8)->get();
        foreach ($checks->where('station_status_id', 8)->get() as $check) {
            $check->update(['station_status_id' => 10]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $stationID
            ]);
            broadcast(new StationNotificationDelete($flag))->toOthers();
        }

        $checks = Station::where('updated_at', '<', $now30min)->where('station_status_id', 9)->get();
        foreach ($checks->where('station_status_id', 9)->get() as $check) {
            $check->update(['station_status_id' => 10]);
            $stationID = $check->id;
            $flag = null;
            $flag = collect([
                'id' => $stationID
            ]);
            broadcast(new StationNotificationDelete($flag))->toOthers();
        }
    }
}
