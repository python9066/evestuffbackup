<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Client;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;

use function GuzzleHttp\json_decode;

class AuthController extends Controller
{

    public function getAuth()
    {
        $data = Auth::all();
        //dd($data);

        return $data;
    }

    public function authcheck()
    {
        $data = Auth::first();
        $expire_date = new DateTime($data->expire_date);
        $date = new DateTime();
        $date = $date->modify("-15 minutes");
        // $date = $date->format('Y-m-d H:i:s');

        if ($date > $expire_date) {
            $data = [false];
        } else {
            $data = [true];
        }
        // dd($data);
        return $data;
    }

    public function AuthURL()
    {
        $client = Client::first();
        $auth = Auth::first();
        $token = $auth->refresh_token;
        $token = 'grant_type=refresh_token&refresh_token=' . $token;
        $http = new GuzzleHttpClient();


        $headers = [
            'Authorization' => 'Basic ' . $client->code,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Host' => 'login.eveonline.com'
        ];
        $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;

        //dd($body);

        $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
            'headers' => $headers,
            'body' => $body
        ]);

        $data = json_decode($response->getBody(), true);

        $date = new DateTime();
        $date = $date->modify("+20 minutes");

        $auth->refresh_token = $data['refresh_token'];
        $auth->access_token = $data['access_token'];
        $auth->expire_date = $date;
        $auth->save();




        //dd($test);
    }
}
