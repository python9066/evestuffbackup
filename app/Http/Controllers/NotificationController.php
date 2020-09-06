<?php

namespace App\Http\Controllers;

use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;

class NotificationController extends Controller
{



    public function getNotifications()
    {
        $status = Helper::checkeve();
        if ($status == 1){
        $type = "note";
        Helper::authcheck();
        $data = Helper::authpull($type);
        Notifications::update($data);
        }

    }

    public function test()
    {
        return view('test');
    }



}
