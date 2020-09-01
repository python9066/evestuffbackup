<?php

namespace App\Http\Controllers;

use utils\Helper\Helper;
use utils\Notifications\Notifications;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        $type = "note";
        Helper::authcheck();
        $data = Helper::authpull($type);
        Notifications::notifications($data);
    }
}
