<?php

namespace App\Http\Controllers;

use App\Events\StationNew;
use App\Events\TowerNew;
use App\Models\Logging;
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

        Logging::create(['campaign_id' => 1, 'campaign_sola_system_id' => 1, 'user_id' => 1, 'text' => $request]);

        // $this->test($request);
        // $data =  $request->toArray();
        // $flag = Notifications::test($data);

        // if ($flag['stationflag'] == 1) {
        //     broadcast(new StationNew($flag['stationflag']))->toOthers();
        // }

        // if ($flag['tower'] == 1) {
        //     broadcast(new TowerNew($flag['towerflag']))->toOthers();
        // }


    }

    public function test($data)
    {

        Logging::create(['campaign_id' => 1, 'campaign_sola_system_id' => 1, 'user_id' => 1, 'text' => $data]);
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
