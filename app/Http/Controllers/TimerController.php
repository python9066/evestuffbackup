<?php

namespace App\Http\Controllers;

use App\TimersRecord;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function getTimerData()
    {

        $data=['timers' => TimersRecord::all(),];
        //dd($data);

        return $data;
    }
}
