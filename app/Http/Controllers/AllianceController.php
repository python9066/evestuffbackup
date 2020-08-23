<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alliance;
use App\Structure;

class AllianceController extends Controller
{
    public function saveAllianceIDs(Request $request)
    {
        Alliance::whereNotNull('id')->update(['active' => 0]);
        $someArray = $request->json()->all();
        foreach ($someArray as $var) {
            DB::table('alliances')->updateOrInsert(['id'=> $var],['active'=>'1']);
        };
        Alliance::where('active', NULL)->delete();
        Alliance::where('active', 0)->delete();
    }

    public function getnewAllianceIDs()
    {
        // $data=[
        //     'id' => Alliance::where('creator_id',null)->pluck('id'),
        // ];

        $data = Alliance::where('name', null)->pluck('id');
        //dd($data);

        return $data;
    }
    public function saveAllianceData(Request $request)
    {
        // DB::table('alliances')->truncate();
        $data = array();
        $id = array();
        $someArray = $request->json()->all();
        //dd($someArray);
        foreach ($someArray as $var) {
            $id = $var['id'];
            $name  = $var['name'];
            $ticker  = $var['ticker'];
            // 'created_at' => now(),
            // 'updated_at' => now()

            $new = Alliance::where('id', $id)->update([
                'name' => $name,
                'ticker' => $ticker
            ]);
        }
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
                $vulnerable_end_time = $var['vulnerable_end_time'];
                $vulnerable_end_time = str_replace("Z", "", $vulnerable_end_time);
                $vulnerable_end_time = str_replace("T", " ", $vulnerable_end_time);      // DD($vulnerable_end_time);
                $vulnerable_start_time = $var['vulnerable_start_time'];
                $vulnerable_start_time = str_replace("Z", "", $vulnerable_start_time);
                $vulnerable_start_time = str_replace("T", " ", $vulnerable_start_time);
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
    }
}
