<?php

namespace App\Http\Controllers;

use App\TimersRecord;
use utils\Timerhelper\Timerhelper;

class TimerController extends Controller
{
    public function getTimerData()
    {
        return ['timers' => TimersRecord::all()];
    }

    public function updateTimerData()
    {
       Timerhelper::update();
    }
}
