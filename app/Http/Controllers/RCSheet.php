<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use utils\Helper\Helper;

class RCSheet extends Controller
{
    public function RCInput(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            $timer = Helper::fixtime($input->Expires);
            dd($input, $timer);
        }
    }
}
