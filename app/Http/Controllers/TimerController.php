<?php

namespace App\Http\Controllers;

use App\TimersRecord;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function getTimerData(Request $request)
    {
        if( $request->query('s') == "blue"){

            $data=['timers' => TimersRecord::where('standing','>','0')->get(),];

        }
        elseif( $request->query('s') == "red"){


            $data=['timers' => TimersRecord::where('standing','<','0.1')->get(),];

        }

        else{

            $data=['timers' => TimersRecord::all(),];
        }
        //dd($query);


        return $data;
    }
}
