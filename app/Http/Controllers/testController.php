<?php

namespace App\Http\Controllers;

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
        // dd($data);
        foreach ($data as $var){

            if ($var['type'] == 'AllAnchoringMsg') {

                    $time = $var['timestamp'];
                    $time = Helper::fixtime($time);
                    $data = array();
                    $text = $var['text'];
                    $text = str_replace("solarSystemID", "system_id", $text);
                    $text = str_replace("structureTypeID", "item_id", $text);
                    $text = Yaml::parse($text);


                    $moon_id = $text['moonID'] ;

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
                        Tower::updateOrCreate('moon_id', $moon_id);
                        $flag = 1;
                    } else {

                        if ($var['notification_id'] > $check->id) {

                            Tower::updateOrCreate('moon_id', $moon_id);
                            $flag = 1;
                        }
                    }
                }


        }
    }
}
