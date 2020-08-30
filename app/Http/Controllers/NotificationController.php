<?php

namespace App\Http\Controllers;
use utils\Helper\Helper;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        Helper::authcheck();
        $url = 'https://esi.evetech.net/latest/characters/717568371/notifications/';
        $data = Helper::authpull($url);
        Helper::notifications($data);

    }
    // 1 = 32226
// 2 = 32458
}
