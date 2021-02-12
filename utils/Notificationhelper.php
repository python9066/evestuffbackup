<?php

namespace utils\Notificationhelper;

use App\Models\Notification;
use App\Models\Temp_notifcation;
use utils\Helper\Helper;
use App\Models\Station;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\Tower;
use GuzzleHttp\Utils;
use Symfony\Component\Yaml\Yaml;


class Notifications
{
    public static function test($data)
    {
        dd($data[0]);
        $current = now();
        $now = $current->modify('-10 minutes');
        $stationflag = 0;
        $towerflag = 0;
        $flag = 0;

        $stationCheck = Station::where('station_status_id', '>', 3)
            ->where('timestamp', '<=', $now)
            ->get()
            ->count();
        if ($stationCheck > 0) {

            Station::where('station_status_id', '>', 3)
                ->where('timestamp', '<=', $now)
                ->update(['station_status_id' => 10]);
        }

        $stationnotenumber = StationNotification::max('id');
        $stationshieldnumber = StationNotificationShield::max('id');
        $stationarmornumber = StationNotificationArmor::max('id');
        $towernumber = Tower::max('id');
        // dd($data);
        foreach ($data as $var) {
            dd($var);

            if ($var['type'] == 'AllAnchoringMsg') {
                if ($var['notification_id'] > $towernumber) {

                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $data = array();
                    $text = $var['text'];
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    $text = Yaml::parse($text);
                    // dd($var, $text);

                    $moon_id = array(
                        'moon_id' => $text['moonID']
                    );


                    $data = array(
                        'id' => $var['notification_id'],
                        'alliance_id' => $text['allianceID'],
                        'item_id' => $text['typeID'],
                        'timestamp' => $time,
                        'tower_status_id' => 1,
                        'user_id' => null,

                    );
                    // dd($var, $text, $moon_id, $data);
                    $check = Tower::where('moon_id', $moon_id)->first();
                    $count = Tower::where('moon_id', $moon_id)->get()->count();
                    if ($count == 0) {
                        Tower::updateOrCreate($moon_id, $data);
                        $towerflag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            Tower::updateOrCreate($moon_id, $data);
                            $towerflag = 1;
                        }
                    }
                }
            } elseif ($var['type'] == 'StructureUnderAttack') {
                if ($var['notification_id'] > $stationnotenumber) {

                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $data = array();
                    $text = $var['text'];
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    $text = Yaml::parse($text);

                    $station_id = array(
                        'station_id' => $text['structureID'],
                    );


                    $stationcheck = Station::where('id', $text['structureID'])->get()->count();
                    if ($stationcheck == 0) {
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
                    }

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'status' => 0,
                    );
                    $check = StationNotification::where('station_id', $station_id)->first();
                    $count = StationNotification::where('station_id', $station_id)->get()->count();
                    if ($count == 0) {
                        StationNotification::updateOrCreate($station_id, $data);
                        $stationflag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            StationNotification::updateOrCreate($station_id, $data);
                            $stationflag = 1;
                        }
                    }
                }
            } elseif ($var['type'] == 'StructureLostShields') {

                if ($var['notification_id'] > $stationshieldnumber) {

                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $data = array();
                    $text = $var['text'];
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    $text = Yaml::parse($text);
                    // dd($var,$text);


                    $station_id = array(
                        'station_id' => $text['structureID'],
                    );

                    $stationcheck = Station::where('id', $text['structureID'])->get()->count();
                    // echo $stationcheck;
                    if ($stationcheck == 0) {
                        Helper::authcheck();
                        $stationdata = Helper::authpull('station', $text['structureID']);

                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'text' => null,
                            'user_id' => null,
                        ]);
                    }

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'status' => 0,

                    );

                    $check = StationNotificationShield::where('station_id', $station_id)->first();
                    $count = StationNotificationShield::where('station_id', $station_id)->get()->count();

                    if ($count == 0) {
                        StationNotificationShield::updateOrCreate($station_id, $data);
                        $stationflag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            StationNotificationShield::updateOrCreate($station_id, $data);
                            $stationflag = 1;
                        }
                    }
                }
            } elseif ($var['type'] == 'StructureLostArmor') {
                if ($var['notification_id'] > $stationarmornumber) {

                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $data = array();
                    $text = $var['text'];
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    $text = Yaml::parse($text);
                    // dd($var,$text);


                    $station_id = array(
                        'station_id' => $text['structureID'],
                    );

                    $stationcheck = Station::where('id', $text['structureID'])->get()->count();
                    // echo $stationcheck;
                    if ($stationcheck == 0) {
                        Helper::authcheck();
                        $stationdata = Helper::authpull('station', $text['structureID']);

                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'text' => null,
                            'user_id' => null,
                        ]);
                    }

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'status' => 0,

                    );

                    $check = StationNotificationArmor::where('station_id', $station_id)->first();
                    $count = StationNotificationArmor::where('station_id', $station_id)->get()->count();

                    if ($count == 0) {
                        StationNotificationArmor::updateOrCreate($station_id, $data);
                        $stationflag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            StationNotificationArmor::updateOrCreate($station_id, $data);
                            $stationflag = 1;
                        }
                    }
                }
            }
        }

        $shield = StationNotificationShield::where('status', 0)->get();
        foreach ($shield as $shield) {
            $station_id = $shield->station_id;
            $check = StationNotification::where('station_id', $station_id)->get();
            if ($check->count() == 1) {

                if ($shield->id > $check->first()->id) {

                    Station::where('id', $shield->station_id)->update(['station_status_id' => 8, 'user_id' => null, 'timestamp' => $shield->timestamp]);
                }
                StationNotificationShield::where('id', $shield->id)->update(['status' => 1]);
            } else {
                StationNotificationShield::where('id', $shield->id)->update(['status' => 1]);
                echo "here";
                Station::where('id', $shield->station_id)->update(['station_status_id' => 8, 'timestamp' => $shield->timestamp]);
            }
        }

        $armor = StationNotificationArmor::where('status', 0)->get();
        foreach ($armor as $armor) {
            $station_id = $armor->station_id;
            $check = StationNotification::where('station_id', $station_id)->get();
            if ($check->count() == 1) {

                if ($armor->id > $check->first()->id) {
                    Station::where('id', $armor->station_id)->update(['station_status_id' => 9, 'user_id' => null, 'timestamp' => $armor->timestamp]);
                }
                StationNotificationArmor::where('id', $armor->id)->update(['status' => 1]);
            } else {
                StationNotificationArmor::where('id', $armor->id)->update(['status' => 1]);
                Station::where('id', $armor->station_id)->update(['station_status_id' => 9, 'timestamp' => $armor->timestamp]);
            }
        }

        return $request = array(
            'stationflag' => $stationflag,
            'towerflag' => $towerflag,
            'notificationflag' => $flag,
        );
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
}
