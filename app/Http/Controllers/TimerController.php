<?php

namespace App\Http\Controllers;

use App\Models\TimersRecord;
use utils\Helper\Helper;
use utils\Timerhelper\Timerhelper;

class TimerController extends Controller
{


    public function getTimerData()
    {
        return ['timers' => TimersRecord::all()];
    }

    public function updateTimerData()
    {
        $status = Helper::checkeve();
        if ($status == 1) {
            Timerhelper::update();
        }
    }
}
