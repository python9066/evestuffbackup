<?php
use App\Models\Auth;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\HotRegion;
use App\Models\Station;
use App\Models\System;
use App\Models\User;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Auth as FacadesAuth;

if (!function_exists('displayName')) {
    function displayName()
    {
        return "Laravel fefefFramework";
    }
}
if (!function_exists('authcheck')) {
    function authcheck()
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
                    'User-Agent' => 'evestuff.online python9066@gmail.com',

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                // echo $body;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body,
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
if (!function_exists('authpull')) {
    function authpull($type, $station_id)
    {
        $type = $type;

        if ($type == "standing") {
            $token = Auth::where('flag_standing', 0)->first();
            // dd($token);
            // echo "auth pull - ";
            if ($token == null) {
                $a = Auth::where('flag_standing', 1)->get();
                foreach ($a as $a) {
                    $a->update(['flag_standing' => 0]);
                }
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
                $a = Auth::where('flag_station', 1)->get();
                foreach ($a as $a) {
                    $a->update(['flag_station' => 0]);
                }
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
            'User-Agent' => 'evestuff.online python9066@gmail.com',

        ];
        $good = 0;

        while ($good == 0) {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false,
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
}
if (!function_exists('fixtime')) {
    function fixtime($time)
    {

        $time = str_replace("Z", "", $time);
        $time = str_replace("T", " ", $time);
        $time = str_replace("+00:00", "", $time);

        return $time;
    }
}
if (!function_exists('clearRemember')) {
    function clearRemember()
    {

        $now = now()->modify('-3 days');
        $u = User::where('updated_at', '<', $now)->get();
        foreach ($u as $u) {
            $u->update(['remember_token' => null]);
        }
    }
}
if (!function_exists('checkeve')) {
    function checkeve()
    {

        $http = new GuzzleHttpCLient();

        $headers = [
            'Accept' => "text/plain",
            'User-Agent' => 'evestuff.online python9066@gmail.com',
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
}
if (!function_exists('eveUserCount')) {
    function eveUserCount()
    {

        $http = new GuzzleHttpCLient();
        $headers = [
            'Accept' => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com',
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/latest/status/?datasource=tranquility', [
            'headers' => $headers,
        ]);
        $status = Utils::jsonDecode($response->getBody());
        return $status->players;
    }
}
if (!function_exists('campaignName')) {
    function campaignName($campaignID)
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
            'system_name' => $systemname,
        ];
    }
}
if (!function_exists('logUpdate')) {
    function logUpdate($campid, $log)
    {
    }
}
if (!function_exists('sheetlogs')) {
    function sheetlogs($log)
    {
    }
}
if (!function_exists('stationlogs')) {
    function stationlogs($log)
    {
    }
}
if (!function_exists('StationRecords')) {
    function StationRecords($type)
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
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 1);
                }]);
            } else {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 0);
                }]);
            }
        }

        if ($type == 5) {
            $station_query->where('show_on_rc_move', 1);
        }

        if ($type == 6) {
            $station_query->where('show_on_coord', 1)->with(['system.webway' => function ($t) {
                $t->where('permissions', 1);
            }]);
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
            'addedBy:id,name',

        ]);

        $stationRecords = $station_query->get();
        return $stationRecords;
    }
}
if (!function_exists('StationRecordsSolo')) {
    function StationRecordsSolo($type, $id)
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

        //rchsheet
        if ($type == 4) {
            $station_query->where('show_on_rc', 1);
            if ($user->can("use_reserved_connection")) {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 1);
                }]);
            } else {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 0);
                }]);
            }
        }

        if ($type == 5) {
            $station_query->where('show_on_rc_move', 1);
        }

        //station sheet
        if ($type == 6) {
            $station_query->where('show_on_coord', 1)->with(['system.webway' => function ($t) {
                $t->where('permissions', 1);
            }]);

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

            'addedBy:id,name',
        ]);

        $stationRecords = $station_query->first();
        return $stationRecords;
    }
}
