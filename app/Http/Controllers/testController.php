<?php

namespace App\Http\Controllers;

use App\Jobs\updateWebway;
use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\Corp;
use App\Models\Station;
use App\Models\testNote;
use App\Models\User;
use App\Models\WebWay;
use DateTime;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use utils\Alliancehelper\Alliancehelper;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function key()
    {

        return User::where('id', 25107)->with('keys')->select('id', 'name')->first();
        // $user = User::find(25107);
        // foreach ($user->keys as $key) {
        //     echo $key->name;
        //     foreach ($key->fleets as $fleet) {
        //         echo $fleet->name;
        //     }
        // }
    }

    public function corptest()
    {

        WebWay::where('id', '>', 0)->update(['active' => 0]);
        $stations = Station::get();
        $stationSystems = $stations->pluck('system_id');
        $campaigns = Campaign::get();
        $campaginSystems = $campaigns->pluck('system_id');

        $systemIDs = $stationSystems->merge($campaginSystems);
        $systemIDs = $systemIDs->unique();
        $systemIDs = $systemIDs->values();
        WebWay::whereIn('system_id', $systemIDs)->update(['active' => 1]);
        WebWay::where('active', 0)->delete();

        foreach ($systemIDs as $system_id) {
            updateWebwayJob::dispatch($system_id)->onQueue('slow');
        }
    }


    public function corptest2()
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        /*
        send = [
            startSystem => start system get from env (1dq)
            endStstem => $system_id
        ]
       return =  api code to request too webway repos $response will be:
            [
                link: UUID of the saved route
                jumps: number of jumps from 1dq ( ID got from env file) to target system
                link_p: UUID of the saved route (with permissions)
                jumps_p: number of jumps from 1dq ( ID got from env file) to target system (with permissions)
            ]
        */

        $startSystem = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
        $webwayURL = env('WEBWAY_URL', ($variables && array_key_exists('WEBWAY_URL', $variables)) ? $variables['WEBWAY_URL'] : null);
        $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);

        $data = [
            'startSystem' => $startSystem,
            'endSystem' => 30000142
        ];

        Http::withToken($webwayToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                "Accept" => "application/json"
            ])->post($webwayURL, $data);
    }


    public function notifications(Request $request)
    {

        testNote::create(['text' => $request]);
        Notifications::test($request, 1);
    }

    public function rc(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {

            dd($input);
        }
    }

    public function userinfo()
    {
        $user = Auth::user()->name;
        $id = Auth::id();
        $current = User::find($id);
        dd($user, $id, $current);
    }


    public function test($id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $url = "https://recon.gnf.lt/api/structure/" . $id;
        // $dance = env('RECON_TOKEN', "DANCE");
        $dance = env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2');
        // $dance2 = env('RECON_USER', 'DANCE2');
        $dance2 = env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2');

        $client = new Client();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data = "Error, Structure Not Found") {
            // echo "NO STATION";
        } else {
            // echo $dance . " - " . $dance2;
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
        }
    }
}
