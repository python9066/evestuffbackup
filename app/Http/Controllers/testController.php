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
use GuzzleHttp\Utils;
use GuzzleHttp\Client as GuzzleHttpClient;

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

    public function test($id)
    {
        $url = "https://recon.gnf.lt/api/structure/" . $id;
        $dance = env('RECON_TOKEN', "DANCE");
        $dance2 = env('RECON_USER', 'DANCE2');

        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'token' =>  env('RECON_TOKEN', "DANCE")

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data = "Error, Structure Not Found") {
            echo "NO STATION";
        } else {
            echo $dance . " - " . $dance2;
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }

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
