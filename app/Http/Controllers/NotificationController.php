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
use App\Events\TowerNew;
use App\Models\StationItems;
use GuzzleHttp\Utils;

class NotificationController extends Controller
{



    public function getNotifications()
    {
    }

    public function test($id)
    {
        $data = Notifications::reconPull($id);

        if (array_key_exists('str_structure_id_md5', $data)) {
            // echo "RECON";
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
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
