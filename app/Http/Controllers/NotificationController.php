<?php

namespace App\Http\Controllers;

use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;

class NotificationController extends Controller
{



    public function getNotifications()
    {

        $type = "note";
        Helper::authcheck();
        $data = Helper::authpull($type);
        Notifications::update($data);

    }
}
