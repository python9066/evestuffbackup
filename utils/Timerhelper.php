<?php

namespace utils\Timerhelper;

use App\Models\Structure;
use App\Models\System;
use GuzzleHttp\Client;
use utils\Helper\Helper;
use GuzzleHttp\Utils;

use function GuzzleHttp\json_decode;

class Timerhelper
{

    public static function update()
    {

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ];
        $url = $url = "https://esi.evetech.net/latest/sovereignty/structures/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response = Utils::jsonDecode($response->getBody(), true);
        // Structure::truncate();
        $data = array();
        // Structure::where('id','>',0)->update(['status' => 1]);
        foreach ($response as $var) {
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
            $data1 = array();

            $check = Structure::where('id', $var['structure_id'])->get()->count();
            if ($check > 0) {
                $status = 1;
            } else {
                $status = 2;
            }

            $data1 = array(
                'alliance_id' => $var['alliance_id'],
                'system_id' => $var['solar_system_id'],
                'item_id' => $var['structure_type_id'],
                'adm' => $adm,
                'vulnerable_end_time' => $vulnerable_end_time,
                'vulnerable_start_time' => $vulnerable_start_time,
                'status' => $status

            );

            // array_push($data, $data1);
            Structure::updateOrCreate(['id' => $var['structure_id']], $data1);
        }
        $now = now();
        Structure::where('status', 0)->delete();
        Structure::where('status', 2)->update(['age' => $now]);
        Structure::where('id', '>', 0)->update(['status' => 0]);
        $system = Structure::where('adm', '>', 0)->select('system_id', 'adm')->get();
        $system = $system->unique('system_id');
        // System::where('id', '>', 0)->update(['adm' => 0]);
        // Structure::where('id','>',0)->update(['status' => 1]);

        foreach ($system as $system) {

            System::where('id', $system->system_id)->update(['adm' => $system->adm]);
        }
    }
}
