<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Temp_notifcation;
use App\TimersRecord;
use DateTime;
use Illuminate\Http\Request;
use utils\Helper\Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');fefefefefefefe
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function party()
    {
        Helper::authcheck();
        $url = 'https://esi.evetech.net/latest/characters/717568371/notifications/';
        $data = Helper::authpull($url);
        //dd($data);
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

                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 1,
                    'si_id' => $si_id = (int)$si_id
                );
                $data2 = array_merge($data, $result);
                // dd($data2);


                $check = Notification::where('si_id', $si_id = (int)$si_id)->get();

                if ($check->count() == 0) {
                    Notification::firstOrCreate($data2);
                } else {
                    $check = $check->toArray();
                    //    dd($check);
                    if ($data2['id'] > $check[0]['id']) {
                        Notification::firstOrCreate($data2);
                    };
                };
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
                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 2,
                    'es_id' => (int)$es_id
                );


                $data2 = array_merge($data, $result);
                $check = Temp_notifcation::where('es_id', $es_id = (int)$es_id)->get();
                if ($check->count() == 0) {
                    Temp_notifcation::firstOrCreate($data2);
                } else {
                    $check = $check->toArray();
                    //    dd($check);
                    if ($data2['id'] > $check[0]['id']) {
                        Temp_notifcation::firstOrCreate($data2);
                    };
                };
            }
        }
        // 1 = 32226
        // 2 = 32458

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


    public function party2()
    {
        $test = Notification::all()->unique('brand');
        $test2 = $test->toJson();
        $data = ['timers' => $test,];
        $test3 = [$test];
        $data2 = ['timers' => TimersRecord::all(),];
        dd($test2, $test3, $data, $data2);
    }
}
