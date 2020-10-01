<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\Tower;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use Symfony\Component\Yaml\Yaml;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function notifications(Request $request)
    {
        $data =  $request->toArray();
        $current = now();
        $now = $current->modify('-10 minutes');
        $stationflag = 0;

        $stationCheck = Station::where('station_status_id', 4)
            ->orWhere('station_status_id', 5)
            ->get()
            ->count();
        if ($stationCheck > 0) {

            Station::where('station_status_id',)
                ->orWhere('station_status_id', 5)
                ->update(['station_status_id', 10]);
        }

        $stationnotenumber = StationNotification::max('id');
        $stationshieldnumber = StationNotificationShield::max('id');
        $stationarmornumber = StationNotificationArmor::max('id');
        // dd($data);
        foreach ($data as $var) {

            if ($var['type'] == 'AllAnchoringMsg') {

                $time = $var['timestamp'];
                $time = Helper::fixtime($time);
                $data = array();
                $text = $var['text'];
                $text = str_replace("solarSystemID", "system_id", $text);
                $text = str_replace("structureTypeID", "item_id", $text);
                $text = Yaml::parse($text);


                $moon_id = array(
                    'moon_id' => $text['moonID']
                );


                $data = array(
                    'id' => $var['notification_id'],
                    'alliance_id' => $var['notification_id'],
                    'item_id' => $var['notification_id'],
                    'timestamp' => $time,
                    'tower_status_id' => 1,
                    'user_id' => null,

                );
                // dd($var, $text, $moon_id, $data);
                $check = Tower::where('moon_id', $moon_id)->first();
                $count = Tower::where('moon_id', $moon_id)->get()->count();
                if ($count == 0) {
                    Tower::updateOrCreate($moon_id, $data);
                    $stationflag = 1;
                } else {

                    if ($var['notification_id'] > $check->id) {

                        Tower::updateOrCreate($moon_id, $data);
                        $stationflag = 1;
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
                    } else {

                        $data = array(
                            'timestamp' => $time,
                            'user_id' => null,
                            'text' => null,
                        );
                        Station::updateOrCreate($station_id, $data);
                    }

                    $data = array(
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
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
                    Station::where('id', $shield->id)->update(['station_status_id' => 4, 'user_id' => null, 'timestamp' => $shield->timestamp]);
                }
                StationNotificationShield::where('id', $shield->id)->update(['status' => 1]);
            } else {
                StationNotificationShield::where('id', $shield->id)->update(['status' => 1]);
                echo "here";
                Station::where('id', $shield->station_id)->update(['station_status_id' => 4, 'timestamp' => $shield->timestamp]);
            }
        }

        $armor = StationNotificationArmor::where('status', 0)->get();
        foreach ($armor as $armor) {
            $station_id = $armor->station_id;
            $check = StationNotification::where('station_id', $station_id)->get();
            if ($check->count() == 1) {

                if ($armor->id > $check->first()->id) {
                    Station::where('id', $armor->id)->update(['station_status_id' => 5, 'user_id' => null, 'timestamp' => $armor->timestamp]);
                }
                StationNotificationArmor::where('id', $armor->id)->update(['status' => 1]);
            } else {
                StationNotificationArmor::where('id', $armor->id)->update(['status' => 1]);
                Station::where('id', $armor->station_id)->update(['station_status_id' => 5, 'timestamp' => $armor->timestamp]);
            }
        }
    }
}
