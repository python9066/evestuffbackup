<?php

namespace App\Http\Controllers;

use App\Events\StationNew;
use App\Events\TowerNew;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use Symfony\Component\Yaml\Yaml;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function notifications(Request $request)
    {
        // $data =  $request->toArray();
        // $flag = Notifications::test($data);

        // if ($flag['stationflag'] == 1) {
        //     broadcast(new StationNew($flag['stationflag']))->toOthers();
        // }

        // if ($flag['tower'] == 1) {
        //     broadcast(new TowerNew($flag['towerflag']))->toOthers();
        // }


    }
}
