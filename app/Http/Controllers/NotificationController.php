<?php

namespace App\Http\Controllers;

use App\Events\NotificationNew;
use App\Models\Notification;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use App\Events\NotificationChanged;
use App\Models\NotificationRecords;

class NotificationController extends Controller
{



    public function getNotifications()
    {
        $status = Helper::checkeve();
        if ($status == 1) {
            echo "1";
            $type = "note";
            $ok = Helper::authcheck();
            echo $ok;
            if ($ok == 1) {
                $data = Helper::authpull($type);
                echo $data;
                $flag = Notifications::update($data);
                // dd($flag);
                if ($flag == 1) {
                    broadcast(new NotificationNew($flag))->toOthers();
                }
            }
        }
    }

    public function test()
    {
        Notifications::test();
    }

    public function update(Request $request, $id)
    {
        Notification::find($id)->update($request->all());
        $notifications =  NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
    }
}
