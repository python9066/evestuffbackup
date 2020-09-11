<?php

namespace utils\Campaignhelper;

use App\Models\Campaign;
use GuzzleHttp\Client;
use utils\Helper\Helper;

use function GuzzleHttp\json_decode;

class Campaignhelper
{

    public static function update()
    {
        Campaign::where('id','>',0)->update(['check' => 0]);
        // dd("fwefe");
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
                    'check' => 1,
                );
                Campaign::updateOrCreate(['id' => $id], $data);
                $after = Campaign::where('id', $id)->get();

                if($before->count() > 0)
                {
                    $attackers_old = $before->toArray();
                    $attackers_old = floatval($attackers_old[0]['attackers_score']);
                    $defenders_old = $before->toArray();
                    $defenders_old = floatval($defenders_old[0]['defenders_score']);
                    $new = $var['attackers_score'];
                    if($new != $attackers_old){
                        echo "diffurent";
                        $flag = 1;
                        Campaign::where('id',$id)->update(['defenders_score_old' => $defenders_old, 'attackers_score_old' => $attackers_old]);
                    };

                }
            }
        }
        // dd("fwefe");
        $now = now();
        $now10 = now()->modify('-10 minutes');
        $yesterday = now('-8 hours');
        $check = Campaign::where('start_time','<=',$now)->where('status_id',1)->count();
        if($check > 0){
            Campaign::where('start_time','<=',$now)
            ->where('status_id',1)
            ->update(['status_id' => 2, 'check' => 1]);
            echo "started";
            $flag = 1;
        }

        $check = Campaign::where('check',0)->count();

        if($check > 0){

            Campaign::where('end',null)
                ->where('check', 0)
                ->update(['end'=> $now, 'status_id' => 3]);
                // ->update(['check' => 1]);
                Campaign::where('end','<=',$now10)
                ->where('check', 0)
                ->where('status_id',3)
                ->update(['status_id' => 10]);


        }

        $finished = Campaign::where('status_id',10)
                    ->where('status_id','3')
                    ->get();
        foreach ($finished as $finished){

            $a = $finished->attackers_score;
            $d = $finished->defenders_score;

            if($a > $d){
                Campaign::where('id',$finished->id)->update(['attackers_score' => 1 ,'defenders_score' => 0]);
            }else{
                Campaign::where('id',$finished->id)->update(['attackers_score' => 0 ,'defenders_score' => 1]);
            }

                $flag = 1;
        }


        return $flag;
    }
}
