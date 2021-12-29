<?php

namespace utils\Alliancehelper;

use GuzzleHttp\Client;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Userlogging;
use utils\Helper\Helper;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Http;

use function GuzzleHttp\json_decode;


class Alliancehelper
{


    public static function getCorpWithNoAlliance($id)
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
                Corp::create([
                    'id' => $var[0]['id'],
                    "name" => $corpReturn["name"],
                    'ticker' => $corpReturn["ticker"],
                    'url' => "https://images.evetech.net/Corporation/" . $var[0]['id'] . "_64.png",
                    'active' => 1
                ]);

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

    public static function updateAlliances()

    {
        Userlogging::create(['url' => "demon alliances", 'user_id' => 9999999999]);
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
        ];

        $url = "https://esi.evetech.net/dev/alliances/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        Alliance::whereNotNull('id')->update(['active' => 0]);
        foreach ($data as $var) {
            Alliance::updateOrCreate(
                ['id' => $var],
                ['active' => 1]
            );
        };
        Alliance::where('active', 0)->delete();



        $allianceID = Alliance::all()->pluck('id');
        Corp::where(' alliance_id', '>', 0)->update(['active' => 0]);
        $errorCount = 100;
        $errorTime = 30;
        for ($i = 0; $i < count($allianceID); $i++) {


            $url = "https://esi.evetech.net/latest/alliances/" . $allianceID[$i] . "/corporations/?datasource=tranquility";
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() == 200) {
                foreach ($response->getHeaders() as $name => $values) {
                    if ($name == "X-Esi-Error-Limit-Remain") {
                        $errorCount = $values;
                    };
                    if ($name == "X-Esi-Error-Limit-Reset") {
                        $errorTime = $values;
                    };
                }
                $errorCount = $errorCount[0] - 1;
                $errorTime = $errorTime[0] + 5;
                if ($errorCount < 50) {
                    sleep($errorTime);
                }
                $corpIDs = Utils::jsonDecode($response->getBody(), true);
                foreach ($corpIDs as $corpID) {
                    Corp::updateOrCreate(
                        ['id' => $corpID],
                        [
                            'active' => 1,
                            'alliance_id' => $allianceID[$i],
                            'url' => "https://images.evetech.net/Corporation/" . $corpID . "_64.png"
                        ]
                    );
                }
            } else {
                sleep($errorTime);
                $i = $i - 1;
            }
        }
        Corp::where('active', 0)->delete();
        $data = Alliance::where('name', null)->pluck('id');
        $errorCount = 100;
        $errorTime = 30;

        for ($i = 0; $i < count($data); $i++) {
            $url = "https://esi.evetech.net/latest/alliances/" . $data[$i] . "/?datasource=tranquility";
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() == 200) {
                foreach ($response->getHeaders() as $name => $values) {
                    if ($name == "X-Esi-Error-Limit-Remain") {
                        $errorCount = $values;
                    };
                    if ($name == "X-Esi-Error-Limit-Reset") {
                        $errorTime = $values;
                    };
                }

                $errorCount = $errorCount[0] - 1;
                $errorTime = $errorTime[0] + 5;

                if ($errorCount < 50) {
                    sleep($errorTime);
                }
                $body = Utils::jsonDecode($response->getBody(), true);

                $body = array(
                    'name' => $body["name"],
                    'ticker' => $body["ticker"],
                    'url' => "https://images.evetech.net/Alliance/" . $data[$i] . "_64.png",
                    'color' => 1
                );
                Alliance::where("id", $data[$i])->update($body);
            } else {
                sleep($errorTime);
                $i = $i - 1;
            }
        }

        $data = Corp::where('name', null)->pluck('id');
        $errorCount = 100;
        $errorTime = 30;

        for ($i = 0; $i < count($data); $i++) {
            $url = "https://esi.evetech.net/latest/corporations/" . $data[$i] . "/?datasource=tranquility";
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false

            ]);

            if ($response->getStatusCode() == 200) {
                foreach ($response->getHeaders() as $name => $values) {
                    if ($name == "X-Esi-Error-Limit-Remain") {
                        $errorCount = $values;
                    };
                    if ($name == "X-Esi-Error-Limit-Reset") {
                        $errorTime = $values;
                    };
                }
                $errorCount = $errorCount[0] - 1;
                $errorTime = $errorTime[0] + 5;
                if ($errorCount < 50) {
                    sleep($errorTime);
                }
                $body = Utils::jsonDecode($response->getBody(), true);

                $body = array(
                    'name' => $body["name"],
                    'ticker' => $body["ticker"],
                    'color' => 1
                );
                Corp::where("id", $data[$i])->update($body);
            } else {
                sleep($errorTime);
                $i = $i - 1;
            }
        }
        Alliance::where('id', '>', 0)->update(['standing' => 0, 'color' => 0]);
        Corp::where('id', '>', 0)->update(['standing' => 0, 'color' => 0]);
        $type = "standing";
        Helper::authcheck();
        $data = Helper::authpull($type, 0);

        foreach ($data as $var) {
            if ($var['standing'] > 0) {
                $color = 2;
            } else {
                $color = 1;
            }
            if ($var['contact_type'] = 'alliance') {
                Alliance::where('id', $var['contact_id'])->update([
                    'standing' => $var['standing'],
                    'color' => $color
                ]);
            };
            if ($var['contact_type'] = 'corporation') {
                Corp::where('id', $var['contact_id'])->update([
                    'standing' => $var['standing'],
                    'color' => $color
                ]);
            };
        };

        Alliance::where('color', '0')->update(['color' => 1]);
        Corp::where('color', '0')->update(['color' => 1]);
        Alliance::where('id', '1354830081')->update(['standing' => 10, 'color' => 3]);
    }
}
