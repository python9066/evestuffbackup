<?php

namespace App\Http\Controllers;

use App\Events\NotificationChanged;
use App\Models\Notification;
use App\Models\NotificationRecords;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $data = getAllNotification();
        return response()->json($data);
    }

    public function test($id)
    {
        $data = reconPull($id);

        if (array_key_exists('str_structure_id_md5', $data)) {
            // echo "RECON";
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
    }

    public function update(Request $request, $id)
    {
        $n = Notification::find($id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        $notifications = NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
    }

    public function addTime(Request $request, $id)
    {
        $n = Notification::find($id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        $notifications = NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
    }
}
