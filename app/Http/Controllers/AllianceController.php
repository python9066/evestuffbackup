<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alliance;

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
