<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Http;

class JobTestController extends Controller
{
    public function standingJob()
    {
        $this->runStandingUpdate();
    }

    public function runStandingUpdate()
    {
        $this->checkKeys();
        $this->updateStanding();
    }

    public function updateStanding()
    {
        $token = Auth::where('flag_standing', 0)->first();
        if ($token == null) {
            Auth::where('flag_standing', 1)->update(['flag_standing' => 0]);
            $token = Auth::where('flag_standing', 0)->first();
            $token->update(['flag_note' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        } else {
            $token->update(['flag_standing' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        }

        $response = Http::withToken($token->access_token)->withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get($url);

        $standings = $response->collect();
        foreach ($standings as $standing) {
            dd($standing);
        }
    }



    public function checkKeys()
    {
        $auths = Auth::all();
        foreach ($auths as $auth) {
            // echo " - " . $auth->name . " - ";

            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                // if (1 > 0) {
                // echo "old!¬¬¬¬¬ !!!! ----";
                $client = Client::first();
                $http = new GuzzleHttpCLient();


                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com'

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                // echo $body;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body
                ]);
                $data = Utils::jsonDecode($response->getBody(), true);
                // dd($data);
                $date = new DateTime();
                $date = $date->modify("+19 minutes");
                $auth->update(['access_token' => $data['access_token'], 'refresh_token' => $data['refresh_token'], 'expire_date' => $date]);
            }
        }
    }
}
