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
        foreach ($data as $var){


            $type = $var['type'];
            if($var['type'] == 'EntosisCaptureStarted')
            {
                $time2 = $var['timestamp'];
                $time = $var['timestamp'];
                $time=Helper::fixtime($time);
                $result=array();
                $data=array();
                $data=array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 1
                );
                $text = $var['text'];
                $text = explode("\n", $text);
                $text= str_replace("solarSystemID","system_id",$text);
                $text= str_replace("structureTypeID","item_id",$text);
                array_pop($text);

                for($i=0; $i<count($text); $i++){
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

                //dd($result);
                $data2 = array_merge($data,$result);
                Notification::firstOrCreate($data2);
                // dd( $result,$data,$data2,$time2);

            }elseif($var['type'] == 'SovStructureReinforced'){
                $time2 = $var['timestamp'];
                $time = $var['timestamp'];
                $time=Helper::fixtime($time);
                $result=array();
                $data=array();
                $data=array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 2
                );

                $text = $var['text'];
                $text = explode("\n", $text);
                $text= str_replace("campaignEventType","event_type_id",$text);
                $text= str_replace("solarSystemID","system_id",$text);
                array_pop($text);

                for($i=0; $i<count($text); $i++){
                    $lines = $text;
                    $keys = explode(':', $lines[$i]);
                    if($keys[0]!== 'decloakTime'){
                    $item = $keys;
                    array_pop($keys);
                    unset($item[0]);
                    $item = array_map('trim', $item);
                    $item[1] = (int)$item[1];
                    $item = array_values($item);
                    $result[$keys[0]] = $item[0];
                    };
                };

                // dd($result);
                $data2 = array_merge($data,$result);
                Temp_notifcation::firstOrCreate($data2);
                  }
        }}


        public function party2()
        {
            $test = TimersRecord::all();
            $test2 = $test->toJson();
            $data = ['timers' => $test,];
            $test3 = [$test];
            $data2 = ['timers' => TimersRecord::all(),];
            dd($test2,$test3,$data,$data2);
        }


}



