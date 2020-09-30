<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use utils\Helper\Helper;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function notifications(Request $data)
    {
        foreach ($data as $var){
            if($var['type' == 'AllAnchoringMsg']){
                $time = $var['timestamp'];

                    $time = Helper::fixtime($time);
                    $result = array();
                    $data = array();
                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    array_pop($text);
                    dd($text);
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
            }

        }
    }
}
