<?php

namespace utils\Helper;

use App\Auth;
use App\Client;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;

use function GuzzleHttp\json_decode;

class Helper
{
    public static function displayName()
    {
        return "Laravel fefefFramework";
    }

    public static function authcheck()
    {

        $auth = Auth::first();
        $expire_date = new DateTime($auth->expire_date);
        $date = new DateTime();

        if ($date > $expire_date) {

            $client = Client::first();
            $http = new GuzzleHttpCLient();

            $headers = [
                'Authorization' => 'Basic ' . $client->code,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Host' => 'login.eveonline.com'

            ];
            $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;

            $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                'headers' => $headers,
                'body' => $body
            ]);

            //dd($response);
            $data = json_decode($response->getBody(), true);
            $date = new DateTime();
            $date = $date->modify("+15 minutes");
            $auth->refresh_token = $data['refresh_token'];
            $auth->access_token = $data['access_token'];
            $auth->expire_date = $date;
            $auth->save();
        }
    }

    public static function authpull($url)
    {

        $token = Auth::first();
        $client = new GuzzleHttpClient();
        $headers = [
            'Authorization' => 'Bearer ' . $token->access_token,

        ];

        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public static function fixtime($time)  {


                $time = str_replace("Z", "", $time);
                $time = str_replace("T", " ", $time);

        return $time;
    }
}
