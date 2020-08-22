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
        // DB::table('alliances')->truncate();
        $data = array();
        $someArray = $request->json()->all();
        foreach ($someArray as $var) {
            $data1 = array();
            $data1 = array(
                'id' => $var,
                // 'created_at' => now(),
                // 'updated_at' => now()
            );
            array_push($data, $data1);
        }
        Alliance::insertOrIgnore($data);
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
        DB::table('structures')->truncate();
        $someArray = $request->json()->all();
        //dd($someArray);

        foreach ($someArray as $var) {
            $count = count($var);

            $alliance_id = $var['alliance_id'];
            $system_id = $var['solar_system_id'];
            $structure_id = $var['structure_id'];
            $structure_type_id = $var['structure_type_id'];
            if($count >"4"){
                $amd = $var['vulnerability_occupancy_level'];
                $vulnerable_end_time = $var['vulnerable_end_time'];
                $vulnerable_start_time = $var['vulnerable_start_time'];
            }else{
                $amd = NULL;
                $vulnerable_end_time = NULL;
                $vulnerable_start_time = NULL;
            }


            $new = Structure::Create([
                'alliance_id' =>$alliance_id,
                'system_id' =>$system_id,
                'structure_id' =>$structure_id,
                'structure_type_id' =>$structure_type_id,
                'amd' =>$amd,
                'vulnerable_end_time' =>$vulnerable_end_time,
                'vulnerable_start_time' =>$vulnerable_start_time,
            ]);
        }
    }
}












// foreach($response->skills as $var) {

//     $var1 = array('typeID'=>$var->typeID,
//                  'skillpoints'=>$var->skillpoints,
//                  'level'=>$var->level,
//                  'published'=>$var->published,
//                  '_fk_characterID'=>$characterID,
//                  '_fk_user_id'=>$_fk_user_id

//                 );

//     $var2 = $var1;
//     unset($var2['typeID']);
//     DB::insertUpdate('corp_skill', $var1,$var2);
