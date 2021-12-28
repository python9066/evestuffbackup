<?php

namespace App\Http\Controllers;

use App\Models\Corp;
use App\Models\testNote;
use App\Models\User;
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

    public function corptest($name)
    {
        $id = $name;
        $response =  Alliancehelper::getCorpWithNoAlliance($id);

        return $response;
    }


    public function corptest2()
    {
        $corpID = null;
        $corpTciker = null;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->post("https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en", ["monty"]);

        $returns = $response->collect();
        foreach ($returns as $key => $var) {
            if ($key == "corporations") {

                $corpRep = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    "Accept" => "application/json"
                ])->get("https://esi.evetech.net/latest/corporations/" . $var[0]['id'] . "/?datasource=tranquility");


                $corpReturn = $corpRep->collect();
                Corp::updateOrCreate(
                    [
                        'id' => $var[0]['id']
                    ],
                    [
                        "name" => $corpReturn["name"],
                        'ticker' => $corpReturn["ticker"],
                        'url' => "https://images.evetech.net/Corporation/" . $var[0]['id'] . "_64.png",
                        'active' => 1
                    ]
                );

                $corpID = $var[0]['id'];
                $corpTciker = $corpReturn["ticker"];
            }
        }

        $tickerlist = Corp::select(['ticker as text', 'id as value'])->get();


        return [
            'ticklist' => $tickerlist,
            'corpID' => $corpID,
            'corpTicker' => $corpTciker
        ];
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
