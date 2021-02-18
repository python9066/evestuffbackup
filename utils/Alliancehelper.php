<?php

namespace utils\Alliancehelper;

use GuzzleHttp\Client;
use App\Models\Alliance;
use App\Models\Corp;
use utils\Helper\Helper;
use GuzzleHttp\Utils;
use function GuzzleHttp\json_decode;


class Alliancehelper
{


    public static function updateAlliances()

    {
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

        $allianceIDs = Alliance::all()->pluck('id');
        Corp::whereNotNull('id')->update(['active' => 0]);
        foreach ($allianceIDs as $allianceID) {


            $url = "https://esi.evetech.net/latest/alliances/" . $allianceID . "/corporations/?datasource=tranquility";
            $response = $client->request('GET', $url, [
                'headers' => $headers
            ]);
            $corpIDs = Utils::jsonDecode($response->getBody(), true);
            foreach ($corpIDs as $corpID) {
                Corp::updateOreCreate(['id' => $corpID, 'active' => 1, 'alliance_id' => $allianceID]);
            }

            dd("yo");
        }

        $data = Alliance::where('name', null)->pluck('id');
        $errorCount = 0;
        $errorTime = 0;
        for ($i = 0; $i < count($data); $i++) {
            $url = "https://esi.evetech.net/latest/alliances/" . $data[$i] . "/?datasource=tranquility";
            $response = $client->request('GET', $url, [
                'headers' => $headers
            ]);
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
            if ($response->getStatusCode() == 200) {
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
        Alliance::where('id', '>', 0)->update(['standing' => 0, 'color' => 0]);
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
        };

        Alliance::where('color', '0')->update(['color' => 1]);
        Alliance::where('id', '1354830081')->update(['standing' => 10, 'color' => 3]);
    }
}
