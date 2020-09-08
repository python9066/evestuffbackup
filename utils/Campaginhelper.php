<?php

namespace utils\Campaginhelper;

use App\Campaign;
use GuzzleHttp\Client;
use utils\Helper\Helper;

use function GuzzleHttp\json_decode;

class Campaginhelper
{

    public static function update()
    {
        $flag = 0;
        $client = New Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $url = "https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility";
        $response = $client->request('GET',$url, [
            'headers' => $headers
        ]);
        $response = json_decode($response->getBody(), true);


        foreach ($response as $var) {

            $event_type = $var['event_type'];
            if($event_type == 'ihub_defense' || $event_type == 'tcu_defense'){
                $id = $var['campaign_id'];
                $before = Campaign::where('id',$id)->get();
                $id1 = array();
                $id1 = array(
                    'id' => $id
                );
                $time = $var['start_time'];
                $start_time = Helper::fixtime($time);
                $data = array();
                $data = array(
                    'attackers_score' => $var['attackers_score'],
                    'constellation_id' => $var['constellation_id'],
                    'alliance_id' => $var['defender_id'],
                    'defenders_score' => $var['defender_score'],
                    'event_type' => $event_type,
                    'system_id' => $var['solar_system_id'],
                    'start_time' => $start_time,
                    'structure_id' => $var['structure_id'],
                );
                Campaign::firstOrCreate($id1,$data);
                $after = Campaign::where('id',$id)->get();
                dd($before, $after);
            }

        }
    }
}
