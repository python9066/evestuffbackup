<?php

namespace utils\Helper;

use App\Events\RcSheetAddLogging;
use App\Models\Auth;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\User;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use App\Events\CampaignSystemUpdate;
use App\Events\EveUserUpdate;
use App\Events\LoggingUpdate;
use App\Events\StationLogUpdate;
use App\Models\Eve;
use App\Models\HotRegion;
use App\Models\Logging;
use App\Models\LoggingType;
use App\Models\Station;
use App\Models\System;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth as FacadesAuth;

use function GuzzleHttp\json_decode;

class Helper
{
    public static function displayName()
    {
        return "Laravel fefefFramework";
    }

    public static function authcheck()
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
                    'Host' => 'login.eveonline.com',
                    'User-Agent' => 'evestuff.online python9066@gmail.com'

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

    public static function authpull($type, $station_id)
    {
        $type = $type;

        if ($type == "standing") {
            $token = Auth::where('flag_standing', 0)->first();
            // dd($token);
            // echo "auth pull - ";
            if ($token == null) {
                Auth::where('flag_standing', 1)->update(['flag_standing' => 0]);
                $token = Auth::where('flag_standing', 0)->first();
                $token->update(['flag_note' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            } else {
                $token->update(['flag_standing' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            }
        } elseif ($type == "note") {
            $token = Auth::where('flag_note', 0)->first();

            if ($token == null) {
                // echo "yo yo yo";
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
        } elseif ($type == "station") {
            $token = Auth::where('flag_station', 0)->first();

            if ($token == null) {
                Auth::where('flag_station', 1)->update(['flag_station' => 0]);
                $token = Auth::where('flag_station', 0)->first();
                $token->update(['flag_station' => 1]);

                $url = "https://esi.evetech.net/latest/universe/structures/" . $station_id . "/?datasource=tranquility";
                // dd($url);
            } else {
                $token->update(['flag_station' => 1]);
                $url = "https://esi.evetech.net/latest/universe/structures/" . $station_id . "/?datasource=tranquility";
                // dd($url);
            }
        }
        // echo $url.":url";
        $client = new GuzzleHttpClient();
        $headers = [
            'Authorization' => 'Bearer ' . $token->access_token,
            'User-Agent' => 'evestuff.online python9066@gmail.com'

        ];
        $good = 0;

        while ($good == 0) {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false
            ]);
            if ($response->getStatusCode() == 200) {
                $data = Utils::jsonDecode($response->getBody(), true);
                $good = 1;
                return $data;
            } else {
                sleep(10);
            }
        }
    }

    public static function fixtime($time)
    {


        $time = str_replace("Z", "", $time);
        $time = str_replace("T", " ", $time);
        $time = str_replace("+00:00", "", $time);

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
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/ping');
        $status = $response->getBody();
        if ($status != "ok") {
            return 0;
        }

        // $headers = [
        //     'Accept' => "application/json",
        // ];

        // $response = $http->request('GET', 'https://esi.evetech.net/latest/status/?datasource=tranquility', [
        //     'headers' => $headers,
        // ]);
        // $status = Utils::jsonDecode($response->getBody());
        // $status = $status->players;

        // if ($status < 10) {
        //     return 0;
        // }

        return 1;
    }

    public static function eveUserCount()
    {

        $http = new GuzzleHttpCLient();
        $headers = [
            'Accept' => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/latest/status/?datasource=tranquility', [
            'headers' => $headers,
        ]);
        $status = Utils::jsonDecode($response->getBody());
        return $status->players;
    }

    public static function campaignName($campaignID)
    {
        $campaign = Campaign::where('id', $campaignID)->first();
        $systemname = $campaign->system->system_name;
        if ($campaign->event_type == 32226) {
            $itemname = "TCU";
        } else {
            $itemname = "IHUB";
        }
        $campaignname = $itemname . " in " . $systemname;
        return $date = [
            'campaign_name' => $campaignname,
            'system_name' => $systemname
        ];
    }

    public static function logUpdate($campid, $log)
    {

        $log = Logging::where('id', $log)->first();
        $timne = Helper::fixtime($log['created_at']);
        $message = [
            'id' => $log['id'],
            'campaign_id' => $log['campaign_id'],
            'campaign_name' => $log['campaign_name'],
            'campaign_sola_system_id' => $log['campaign_sola_system_id'],
            'sola_system_name' => $log['sola_system_name'],
            'campaign_system_id' => $log['campaign_system_id'],
            'user_id' => $log['user_id'],
            'user_name' => $log->user()->value('name'),
            'logging_type_id' => $log['logging_type_id'],
            'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
            'text' => $log['text'],
            'created_at' => $timne
        ];



        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new LoggingUpdate($flag));
    }

    public static function sheetlogs($log)
    {

        $log = Logging::where('id', $log)->first();
        $time = Helper::fixtime($log['created_at']);
        $message = [
            'id' => $log['id'],
            'user_id' => $log['user_id'],
            'user_name' => $log->user()->value('name'),
            'logging_type_id' => $log['logging_type_id'],
            'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
            'station_id' => $log['station_id'],
            'station_name' => Station::where('id', $log['station_id'])->value('name'),
            'text' => $log['text'],
            'created_at' => $time
        ];



        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetAddLogging($flag));
    }

    public static function stationlogs($log)
    {

        $log = Logging::where('id', $log)->first();
        $time = Helper::fixtime($log['created_at']);
        $message = [
            'id' => $log['id'],
            'user_id' => $log['user_id'],
            'user_name' => $log->user()->value('name'),
            'logging_type_id' => $log['logging_type_id'],
            'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
            'station_id' => $log['station_id'],
            'station_name' => Station::where('id', $log['station_id'])->value('name'),
            'text' => $log['text'],
            'created_at' => $time
        ];



        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationLogUpdate($flag));
    }


    public static function StationRecords($type)
    {
        $regionIDs = HotRegion::where('show_fcs', 1)->pluck('region_id');
        $systemIDs = System::whereIn('region_id', $regionIDs)->pluck('id');
        $user = FacadesAuth::user();
        $type = $type;
        $station_query = Station::query();
        if ($type == 1) {
            $station_query->where('show_on_main', 1);
        }

        if ($type == 2) {
            $station_query->where('show_on_chill', 1);
        }

        if ($type == 3) {
            $station_query->where('show_on_welp', 1);
        }

        if ($type == 4) {
            $station_query->where('show_on_rc', 1);
            if ($user->can("use_reserved_connection")) {
                $station_query->with(['system.webway'
                => function ($t) {
                    $t->where('permissions', 1);
                },]);
            } else {
                $station_query->with(['system.webway'
                => function ($t) {
                    $t->where('permissions', 0);
                },]);
            }
        }

        if ($type == 5) {
            $station_query->where('show_on_rc_move', 1);
        }

        if ($type == 6) {
            $station_query->where('show_on_coord', 1)->with(['system.webway'
            => function ($t) {
                $t->where('permissions', 1);
            },]);
            if ($user->can('view_coord_sheet')) {
            } else {
                $station_query->where('show_on_coord', 1)->whereIn('system_id', $systemIDs);
            }
            $station_query->where('standing', '=<', 0);
        }



        $station_query->with([
            'system',
            'system.constellation',
            'system.region',
            'status:id,name',
            'fc.user:id,name',
            'recon.user:id,name',
            'gsoluser.user:id,name',
            'corp:id,alliance_id,name,ticker,standing,url,color',
            'corp.alliance:id,name,ticker,standing,url,color',
            'item',
            'fit:id,item_name',
            'logs:id,station_id,user_id,logging_type_id,text,created_at',
            'logs.type:id,name',
            'logs.user:id,name',
            'addedBy:id,name'

        ]);

        $stationRecords = $station_query->get();
        return $stationRecords;
    }

    public static function StationRecordsSolo($type, $id)
    {

        $regionIDs = HotRegion::where('show_fcs', 1)->pluck('region_id');
        $systemIDs = System::whereIn('region_id', $regionIDs)->pluck('id');
        $user = FacadesAuth::user();
        $type = $type;
        $station_query = Station::query();
        if ($user->can('view_coord_sheet')) {
        } else {
            $station_query->whereIn('system_id', $systemIDs);
        }
        if ($type == 1) {
            $station_query->where('show_on_main', 1);
        }

        if ($type == 2) {
            $station_query->where('show_on_chill', 1);
        }

        if ($type == 3) {
            $station_query->where('show_on_welp', 1);
        }

        if ($type == 4) {
            $station_query->where('show_on_rc', 1);
            if ($user->can("use_reserved_connection")) {
                $station_query->with(['system.webway'
                => function ($t) {
                    $t->where('permissions', 1);
                },]);
            } else {
                $station_query->with(['system.webway'
                => function ($t) {
                    $t->where('permissions', 0);
                },]);
            }
        }

        if ($type == 5) {
            $station_query->where('show_on_rc_move', 1);
        }

        if ($type == 6) {
            $station_query->where('show_on_coord', 1)->with(['system.webway'
            => function ($t) {
                $t->where('permissions', 1);
            },]);
            if ($user->can('view_coord_sheet')) {
            } else {
                $station_query->where('show_on_coord', 1)->whereIn('system_id', $systemIDs);
            }
        }

        $station_query->where('standing', '=<', 0);
        $station_query->where('id', $id);
        $station_query->with([
            'system',
            'system.constellation',
            'system.region',
            'status:id,name',
            'fc.user:id,name',
            'recon.user:id,name',
            'gsoluser.user:id,name',
            'corp:id,alliance_id,name,ticker,standing,url,color',
            'corp.alliance:id,name,ticker,standing,url,color',
            'item',
            'fit:id,item_name',
            'logs:id,station_id,user_id,logging_type_id,text,created_at',
            'logs.type:id,name',
            'logs.user:id,name',
            'addedBy:id,name',
        ]);

        $stationRecords = $station_query->first();
        return $stationRecords;
    }
}
