<?php

namespace App\Http\Controllers;

use App\TimersRecord;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function getTimerData()
    {

        //------- with us a link like /getTimerData?s=all anything that is "?s=" will be in the request
        // if( $request->query('s') == "blue"){

        //     $data=['timers' => TimersRecord::where('standing','>','0')->get(),];

        // }



        return [ 'timers' => TimersRecord::all()];

        //dd($query);


        //  $data->json();
    }
}
