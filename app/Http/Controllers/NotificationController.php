<?php

namespace App\Http\Controllers;

use App\Events\NotificationNew;
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
        $flag = Notifications::update($data);
            // dd($flag);
        if($flag == 1){
            broadcast(new NotificationNew($flag))->toOthers();
        }

        }

    }

    public function test()
    {
        Notifications::test();
    }



}
