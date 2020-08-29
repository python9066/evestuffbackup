<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getAuth()
    {
        $data = Auth::all();
        //dd($data);

        return $data;
    }
    // 1 = 32226
// 2 = 32458
}
