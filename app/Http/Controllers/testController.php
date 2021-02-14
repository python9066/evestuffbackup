<?php

namespace App\Http\Controllers;

use App\Events\StationNew;
use App\Events\TowerNew;
use App\Models\Logging;
use App\Models\Station;
use App\Models\testNote;
use DateTime;
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

        testNote::create(['text' => $request]);
        Notifications::test($request);

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

    public function test()
    {
        $dance = env("RECON_TOKEN", "DANCE");

        dd($dance);

        // $outTime = null;
        // $ldap = $time;
        // $winSecs       = (int)($ldap / 10000000);
        // $unixTimestamp = ($winSecs - 11644473600);
        // $outTime = date("Y-m-d H:i:s", $unixTimestamp);

        // Station::where('id', $id)->update(['out_time' => $outTime]);

        // Logging::create(['campaign_id' => 1, 'campaign_sola_system_id' => 1, 'user_id' => 1, 'text' => $data]);

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
