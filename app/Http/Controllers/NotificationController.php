<?php

namespace App\Http\Controllers;
use utils\Helper\Helper;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        $type = "note";
        Helper::authcheck();
        $data = Helper::authpull($type);
        Helper::notifications($data);

    }
    // 1 = 32226
// 2 = 32458
}
