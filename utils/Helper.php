<?php

namespace utils\Helper;

use App\Models\Auth;
use App\Models\Client;
use App\Models\User;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;

use function GuzzleHttp\json_decode;

class Helper
{
    public static function displayName()
    {
        return "Laravel fefefFramework";
    }

    public static function authcheck()
    {
        $auth = Auth::where('active', 1)->get();
        foreach ($auth as $auth) {
            // echo $auth->char_id;

            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                echo "old";
                $client = Client::first();
                $http = new GuzzleHttpCLient();


                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com'

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                echo "refresh". $auth->user_id;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body
                ]);

                $statuscode = $response->getStatusCode();
                // dd($statuscode);


                if ($statuscode == 200) {
                    $data = Utils::jsonDecode($response->getBody(), true);
                    dd($data);
                    $date = new DateTime();
                    $date = $date->modify("+15 minutes");
                    $auth->refresh_token = $data['refresh_token'];
                    $auth->access_token = $data['access_token'];
                    $auth->expire_date = $date;
                    $auth->save();
                    return 1;
                } else {
                    $auth->active = 0;
                    $auth->save();
                    return 0;
                }
            }
            return 1;
        }
    }

    public static function authpull($type)
    {
        $type = $type;

        if ($type == "standing") {
            $token = Auth::where('flag_standing', 0)
                ->where('active', 1)->first();
            // dd($token);
            echo "auth pull - ";
            if ($token == null) {
                Auth::where('flag_standing', 1)->update(['flag_standing' => 0]);
                $token = Auth::where('flag_standing', 0)->first();
                $token->update(['flag_note' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            } else {
                $token->update(['flag_standing' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            }
        } else {
            $token = Auth::where('flag_note', 0)->first();

            if ($token == null) {
                echo "yo yo yo";
                Auth::where('flag_note', 1)->update(['flag_note' => 0]);
                $token = Auth::where('flag_standing', 0)
                    ->where('active', 1)->first();
                $token->update(['flag_note' => 1]);

                $url = "https://esi.evetech.net/latest/characters/" . $token->char_id . "/notifications/";
                // dd($url);
            } else {
                $token->update(['flag_note' => 1]);
                $url = "https://esi.evetech.net/latest/characters/" . $token->char_id . "/notifications/";
                // dd($url);
            }
        }
        // echo $url.":url";
        $client = new GuzzleHttpClient();
        $headers = [
            'Authorization' => 'Bearer ' . $token->access_token,

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        // echo $data;
        return $data;
    }

    public static function fixtime($time)
    {


        $time = str_replace("Z", "", $time);
        $time = str_replace("T", " ", $time);

        return $time;
    }


    public static function clearRemember()
    {

        $now = now()->modify('-3 days');
        User::where('updated_at', '<', $now)->update(['remember_token' => null]);
    }


    public static function checkeve()
    {

        $http = new GuzzleHttpCLient();

        $headers = [
            'Accept' => "text/plain",
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/ping');
        $status = $response->getBody();
        if ($status != "ok") {
            return 0;
        }

        $headers = [
            'Accept' => "application/json",
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/latest/status/?datasource=tranquility', [
            'headers' => $headers,
        ]);
        $status = Utils::jsonDecode($response->getBody());
        $status = $status->players;

        if ($status < 10) {
            return 0;
        }

        return 1;
    }
}
