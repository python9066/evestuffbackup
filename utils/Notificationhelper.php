<?php

namespace utils\Notificationhelper;

use App\Notification;
use App\Temp_notifcation;
use utils\Helper\Helper;


class Notifications
{
    public static function test ()
    {
        Notification::updateOrCreate(['si_id' => 3000474132226],['id' => 999999999999]);


    }#



    public static function update($data)
    {
        $now = now('-10 minutes');
        $yesterday = now('-6 hours');
        dd($yesterday);
        $flag = 0;

        $check =Notification::where('status_id',2)
                    ->where('timestamp','>=',$now)
                    ->count();
                    // dd($check);
                    if($check > 0){

                        Notification::where('status_id',2)
                        ->where('timestamp','>=',$now)
                        ->update(['status_id' => 10]);
                        $flag = 1;
                        $check = null;
                    }

        $check =Notification::where('status_id',4)
        ->where('timestamp','>=',$now)
        ->count();

        if($check > 0){
            Notification::where('status_id',4)
                        ->where('timestamp','>=',$now)
                        ->update(['status_id' => 10]);
                        $flag = 1;
                        $check = null;
        }


        $notenumber = Notification::max('id');
        $tempnumber = Temp_notifcation::max('id');
        foreach ($data as $var) {


            if ($var['type'] == 'EntosisCaptureStarted') {
                if($var['notification_id'] > $notenumber){



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

                );
                $data2 = array_merge($data, $result);
                $check = Notification::where('si_id',$check_si_id)->get();
                $count = $check->count();
                if($count == 0){
                    Notification::updateOrCreate($si_id,$data2);
                    $flag = 1;
                }else{

                    if($var['notification_id'] > $check->max('id')){

                        Notification::updateOrCreate($si_id,$data2);
                        $flag = 1;
                    }

                }


            }} elseif ($var['type'] == 'SovStructureReinforced') {
                if($var['notification_id'] > $tempnumber){
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
                $check = Temp_notifcation::where('es_id',$check_es_id)->get();
                $count = $check->count();
                if($count == 0){
                    Temp_notifcation::updateOrCreate($es_id,$data2);
                    $flag = 1;
                }else{
                    if($var['notification_id'] > $check->max('id')){

                        Temp_notifcation::updateOrCreate($es_id,$data2);
                        $flag = 1;
                    }

                }

            }}
        }


        $tempnote = Temp_notifcation::where('status', 0)->get();
        foreach ($tempnote as $tempnote) {
            if ($tempnote->event_type_id == 1) {
                $stype = 32226;
            } else {
                $stype = 32458;
            }

            $si_id = $tempnote->system_id.$stype;
            $si_id = (int)$si_id;
            $check = Notification::where('si_id', $si_id)->get();
                if ($check->count() == 1) {

                    $check = $check->toArray();

                if ($tempnote->id > $check[0]['id']) {

                    Notification::where('si_id', $si_id)
                                ->where('item_id', $stype)
                                ->update(['status_id' => 2]);
                }
                Temp_notifcation::where('id', $tempnote->id)->update(['status' => 1]);
            } else {

                Temp_notifcation::where('id', $tempnote->id)->update(['status' => 1]);
            }
        }


        return $flag;
    }



}
