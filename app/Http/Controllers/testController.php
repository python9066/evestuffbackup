<?php

namespace App\Http\Controllers;


use App\Models\testNote;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Utils;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Auth;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function key()
    {
        $test = User::where('id', 25107)->with('keys');
        dd($test);
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
    }
}
