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
use App\Events\StationNew;
use App\Events\TowerNew;
use GuzzleHttp\Utils;

class NotificationController extends Controller
{



    public function getNotifications()
    {
        $status = Helper::checkeve();
        if ($status == 1) {
            echo " status 1 - ";
            $type = "note";
            Helper::authcheck();
            $data = Helper::authpull($type, 0);
            $flag = Notifications::update($data);
            if ($flag['notificationflag']) {
                broadcast(new NotificationNew($flag['notificationflag']))->toOthers();
            }
            if ($flag['stationflag'] == 1) {
                broadcast(new StationNew($flag['stationflag']))->toOthers();
            }

            if ($flag['tower'] == 1) {
                broadcast(new TowerNew($flag['towerflag']))->toOthers();
            }
        }
    }

    public function test($id)
    {
        $data = Notifications::reconPull($id);
        // $text = Utils::jsonDecode($data['str_fitting'], true);;
        if ($data['str_structure_id_md5'] != null) {
            echo "RECON";
        }

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function update(Request $request, $id)
    {
        Notification::find($id)->update($request->all());
        $notifications =  NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
    }

    public function addTime(Request $request, $id)
    {
        Notification::find($id)->update($request->all());
        $notifications =  NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
    }
}
