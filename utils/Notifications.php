<?php

namespace utils\Notifications;

use App\Notification;
use App\Temp_notifcation;
use utils\Helper\Helper;


class Notifications
{

    public static function notifications($data)
    {
        foreach ($data as $var) {


            $type = $var['type'];
            if ($var['type'] == 'EntosisCaptureStarted') {

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
                }else{

                    if($var['notification_id'] > $check->first()->id){

                        Notification::updateOrCreate($si_id,$data2);
                    }

                }


            } elseif ($var['type'] == 'SovStructureReinforced') {
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
                );

                // ($data2);
                $data2 = array_merge($data, $result);
                $check = Temp_notifcation::where('es_id',$check_es_id)->get();
                $count = $check->count();
                if($count == 0){
                    Temp_notifcation::updateOrCreate($es_id,$data2);
                }else{
                    if($var['notification_id'] > $check->first()->id){

                        Temp_notifcation::updateOrCreate($es_id,$data2);
                    }

                }

            }
        }


        $note = Temp_notifcation::where('status', 0)->get();
        foreach ($note as $note) {

            if ($note->event_type_id = 1) {
                $stype = 32226;
            } else {
                $stype = 32458;
            }

            // dd($note->system_id);
            $check = Notification::where('system_id', $note->system_id)
                ->where('item_id', $stype)->get();
            if ($check->count() == 1) {

                $check = $check->toArray();
                if ($note->timestamp > $check[0]['timestamp']) {

                    Notification::where('system_id', $note->system_id)
                        ->where('item_id', $stype)->update(['status_id' => 2]);
                }
                Temp_notifcation::where('id', $note->id)->update(['status' => 1]);
            } else {

                Temp_notifcation::where('id', $note->id)->update(['status' => 1]);
            }
        }
    }



}
