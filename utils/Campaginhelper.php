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
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $url = "https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response = json_decode($response->getBody(), true);


        foreach ($response as $var) {

            $event_type = $var['event_type'];
            if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                if($event_type =='ihub_defense'){
                    $event_type = 32458;
                }else{
                    $event_type = 32226;
                }
                $id = $var['campaign_id'];
                $before = Campaign::where('id', $id)->get();
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
                Campaign::updateOrCreate(['id' => $id], $data);
                $after = Campaign::where('id', $id)->get();
                $diff = $before->diffAssoc($after)->count();
                if ($diff > 0) {
                    $flag = 1;
                }
            }
        }
        return $flag;
    }
}
