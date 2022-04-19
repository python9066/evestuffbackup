<?php

namespace App\Http\Controllers;

use App\Jobs\updateWebway;
use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\Corp;
use App\Models\NewOperation;
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
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class testController extends Controller
{
    use HasRoles;
    use HasPermissions;
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

    public static function getCorpWithNoAlliance()
    {

        $corpID = null;
        $corpTciker = null;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->post("https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en", ["monty"]);

        $returns = $response->collect();
        dd($returns);
        foreach ($returns as $key => $var) {
            if ($key == "corporations") {

                $corpRep = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    "Accept" => "application/json",
                    'User-Agent' => 'evestuff.online python9066@gmail.com'
                ])->get("https://esi.evetech.net/latest/corporations/" . $var[0]['id'] . "/?datasource=tranquility");



                $corpReturn = $corpRep->collect();
                // Corp::create([
                //     'id' => $var[0]['id'],
                //     "name" => $corpReturn["name"],
                //     'ticker' => $corpReturn["ticker"],
                //     'url' => "https://images.evetech.net/Corporation/" . $var[0]['id'] . "_64.png",
                //     'active' => 1
                // ]);

                // $corpID = $var[0]['id'];
                // $corpTciker = $corpReturn["ticker"];
            }
        }

        // $tickerlist = Corp::select(['ticker as text', 'id as value'])->get();


        // return [
        //     'ticklist' => $tickerlist,
        //     'corpID' => $corpID,
        //     'corpTicker' => $corpTciker
        // ];
    }

    public function prequal()
    {

        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a524f35da058742f0defd6fb0db6afc4');
        } else {
            return null;
        }
    }

    public function getSoloOperations()
    {

        $user = Auth::user();
        if ($user->can('super')) {

            return ['operations' => NewOperation::where('solo', 1)
                ->with([
                    'campaign',
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,name,adm',
                    'campaign.system.webway',
                    'campaign.structure:id,item_id,age',
                ])
                ->get()];
        } else {
            return null;
        }
    }

    public function horizon()
    {

        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a3bc619080ec6c04c44fffff8cc780de');
        } else {
            return null;
        }
    }

    public function testGetAlliance($id)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/alliances/" . $id . "/?datasource=tranquility");
        $allianceInfo = $response->collect();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/alliances/" . $id . "/corporations/?datasource=tranquility");
        $corpIDs = $response->collect();

        dd($allianceInfo, $corpIDs);
    }


    public function testStationRecords($type)
    {
        return Helper::StationRecords($type);
    }


    public function testPull()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ];
        $url = "https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response = Utils::jsonDecode($response->getBody(), true);
        dd($response);
    }

    public function campaginTest()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility");

        $campaigns = $response->collect();
        foreach ($campaigns as $campaign) {
            $test = NewOperation::where('id', 12)->with(['campaign'])->first();
            $text = ["test" => $test];
            return $test;
        }
    }

    public function campaginListTest()
    {
        if (Auth::user()->can('super')) {

            $list = NewOperation::where('solo', 1)
                ->with([
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.structure:id,age'
                ])
                ->get();
            return ['list' => $list];
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
                "Accept" => "application/json",
                'User-Agent' => 'evestuff.online python9066@gmail.com'
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
