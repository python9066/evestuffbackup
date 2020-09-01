<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Structure;
use App\System;
use utils\Alliancehelper\Alliancehelper;
use utils\Helper\Helper;


//hierhere
class AllianceController extends Controller
{


    public function updateAlliances()

    {
        Alliancehelper::updateAlliances();
    }

    public function saveTimers(Request $request)
    {
        $data = array();
        DB::table('structures')->truncate();
        $someArray = $request->json()->all();
        //dd($someArray);

        foreach ($someArray as $var) {
            $count = count($var);
            if ($count > 4) {
                $amd = $var['vulnerability_occupancy_level'];
                $time = $var['vulnerable_end_time'];
                $vulnerable_end_time = Helper::fixtime($time);
                $time = $var['vulnerable_start_time'];
                $vulnerable_start_time = Helper::fixtime($time);
            } else {
                $amd = NULL;
                $vulnerable_end_time = NULL;
                $vulnerable_start_time = NULL;
            }
            $data1 = array();

            $data1 = array(
                'alliance_id' => $var['alliance_id'],
                'system_id' => $var['solar_system_id'],
                'id' => $var['structure_id'],
                'item_id' => $var['structure_type_id'],
                'amd' => $amd,
                'vulnerable_end_time' => $vulnerable_end_time,
                'vulnerable_start_time' => $vulnerable_start_time,

            );


            array_push($data, $data1);
        }
        DB::table('structures')->insertOrIgnore($data);
        $system = Structure::where('amd', '>', 0)->select('system_id', 'amd')->get();
        $system = $system->unique('system_id');
        System::where('id', '>', 0)->update(['adm' => 0]);
        foreach ($system as $system) {
            System::where('id', $system->system_id)->update(['adm' => $system->amd]);
        }
    }
}
