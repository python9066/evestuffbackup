<?php

namespace utils\Helper;

use App\Auth;
use App\Client;
use App\Notification;
use App\Temp_notifcation;
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
        $auth = Auth::all();
        foreach ($auth as $auth) {


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
    }

    public static function authpull($type)
    {
        $type = $type;

        if ( $type == "standing") {
            $token = Auth::where('flag_standing', 0)->first();
            // $count = $token->count();

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
                Auth::where('flag_note', 1)->update(['flag_note' => 0]);
                $token = Auth::where('flag_note', 0)->first();
                $token->update(['flag_note' => 1]);
                $url = "https://esi.evetech.net/latest/characters/" . $token->char_id . "/notifications/";
                // dd($url);
            } else {
                $token->update(['flag_note' => 1]);
                $url = "https://esi.evetech.net/latest/characters/" . $token->char_id . "/notifications/";
                // dd($url);
            }
        }
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

    public static function fixtime($time)
    {


        $time = str_replace("Z", "", $time);
        $time = str_replace("T", " ", $time);

        return $time;
    }

    public static function notifications($data)
    {
        foreach ($data as $var) {


            $type = $var['type'];
            if ($var['type'] == 'EntosisCaptureStarted') {

                //dd($time2);
                $time = $var['timestamp'];

                $time = Helper::fixtime($time);
                $result = array();
                $data = array();
                $text = $var['text'];
                $text = explode("\n", $text);
                $text = str_replace("solarSystemID", "system_id", $text);
                $text = str_replace("structureTypeID", "item_id", $text);
                array_pop($text);

                for ($i = 0; $i < count($text); $i++) {
                    $lines = $text;
                    $keys = explode(':', $lines[$i]);
                    $item = $keys;
                    array_pop($keys);
                    unset($item[0]);
                    $item = array_map('trim', $item);
                    $item[1] = (int)$item[1];
                    $item = array_values($item);
                    $result[$keys[0]] = $item[0];
                };
                $si_id = $result['system_id'] . $result['item_id'];

                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 1,
                    'si_id' => $si_id = (int)$si_id
                );
                $data2 = array_merge($data, $result);
                // dd($data2);


                $check = Notification::where('si_id', $si_id = (int)$si_id)->get();

                if ($check->count() == 0) {
                    Notification::firstOrCreate($data2);
                } else {
                    $check = $check->toArray();
                    //    dd($check);
                    if ($data2['id'] > $check[0]['id']) {
                        Notification::firstOrCreate($data2);
                    };
                };
            } elseif ($var['type'] == 'SovStructureReinforced') {
                $time2 = $var['timestamp'];
                $time = $var['timestamp'];
                $time = Helper::fixtime($time);
                $result = array();
                $data = array();

                $text = $var['text'];
                $text = explode("\n", $text);
                $text = str_replace("campaignEventType", "event_type_id", $text);
                $text = str_replace("solarSystemID", "system_id", $text);
                array_pop($text);

                for ($i = 0; $i < count($text); $i++) {
                    $lines = $text;
                    $keys = explode(':', $lines[$i]);
                    if ($keys[0] !== 'decloakTime') {
                        $item = $keys;
                        array_pop($keys);
                        unset($item[0]);
                        $item = array_map('trim', $item);
                        $item[1] = (int)$item[1];
                        $item = array_values($item);
                        $result[$keys[0]] = $item[0];
                    };
                };
                $es_id = $result['event_type_id'] . $result['system_id'];
                $data = array(
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                    'notification_type_id' => 2,
                    'es_id' => (int)$es_id
                );


                $data2 = array_merge($data, $result);
                $check = Temp_notifcation::where('es_id', $es_id = (int)$es_id)->get();
                if ($check->count() == 0) {
                    Temp_notifcation::firstOrCreate($data2);
                } else {
                    $check = $check->toArray();
                    //    dd($check);
                    if ($data2['id'] > $check[0]['id']) {
                        Temp_notifcation::firstOrCreate($data2);
                    };
                };
            }
        }


        $note = Temp_notifcation::where('status', 0)->get();
        foreach ($note as $note) {

            if ($note->event_type_id = 1) {
                $stype = 32226;
            } else {
                $stype = 32458;
            }

            // dd($note->system_id);
            $check = Notification::where('system_id', $note->system_id)
                ->where('item_id', $stype)->get();
            if ($check->count() == 1) {

                $check = $check->toArray();
                if ($note->timestamp > $check[0]['timestamp']) {

                    Notification::where('system_id', $note->system_id)
                        ->where('item_id', $stype)->update(['status_id' => 2]);
                }
                Temp_notifcation::where('id', $note->id)->update(['status' => 1]);
            } else {

                Temp_notifcation::where('id', $note->id)->update(['status' => 1]);
            }
        }
    }
}
