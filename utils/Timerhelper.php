<?php

namespace utils\Timerhelper;

use App\Structure;
use App\System;
use GuzzleHttp\Client;
use utils\Helper\Helper;

use function GuzzleHttp\json_decode;

class Timerhelper
{

    public static function update()
    {

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
        ];
        $url = "https://esi.evetech.net/latest/sovereignty/structures/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response = json_decode($response->getBody(), true);
        Structure::where('id','>',0)->update(['item_id' => 0]);

        foreach ($response as $var) {
            $id = array();
            $id = array(
                'id' => $var['structure_id'],
            );
            $count = count($var);
            if ($count > 4) {
                $adm = $var['vulnerability_occupancy_level'];
                $time = $var['vulnerable_end_time'];
                $vulnerable_end_time = Helper::fixtime($time);
                $time = $var['vulnerable_start_time'];
                $vulnerable_start_time = Helper::fixtime($time);
            } else {
                $adm = NULL;
                $vulnerable_end_time = NULL;
                $vulnerable_start_time = NULL;
            }
            $data = array();
            $data = array(
                'alliance_id' => $var['alliance_id'],
                'system_id' => $var['solar_system_id'],
                'item_id' => $var['structure_type_id'],
                'adm' => $adm,
                'vulnerable_end_time' => $vulnerable_end_time,
                'vulnerable_start_time' => $vulnerable_start_time,

            );

            Structure::firstOrCreate($id,$data);

        }
            Structure::where('item_id',0)->delete();
        $system = Structure::where('adm', '>', 0)->select('system_id', 'adm')->get();
        $system = $system->unique('system_id');
        System::where('id', '>', 0)->update(['adm' => 0]);

        foreach ($system as $system) {

            System::where('id', $system->system_id)->update(['adm' => $system->adm]);
        }
    }
}
