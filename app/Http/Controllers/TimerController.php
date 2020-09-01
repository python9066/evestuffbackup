<?php

namespace App\Http\Controllers;

use App\TimersRecord;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function getTimerData()
    {
        return ['timers' => TimersRecord::all()];
    }
}
