<?php

namespace App\Http\Controllers;

use App\Jobs\updateWebway;
use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\Corp;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use App\Models\Region;
use App\Models\Station;
use App\Models\System;
use App\Models\testNote;
use App\Models\User;
use App\Models\WebWay;
use DateTime;
use Illuminate\Http\Request;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use utils\Alliancehelper\Alliancehelper;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use utils\Broadcasthelper\Broadcasthelper;
use Illuminate\Support\Str;

class testController extends Controller
{
    use HasRoles;
    use HasPermissions;
    public function index()
    {
        return view('test2');
    }

    public function removeFC()
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $users = User::all();
            foreach ($users as $user) {
                $user->removeRole(12);
            }
        }
    }

    public function key()
    {

        return User::where('id', 25107)->with('keys')->select('id', 'name')->first();
        // $user = User::find(25107);
        // foreach ($user->keys as $key) {
        //     echo $key->name;
        //     foreach ($key->fleets as $fleet) {
        //         echo $fleet->name;
        //     }
        // }
    }


    public function testUpdateScore(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('super')) {
            NewCampaign::where('id', $id)->update($request->all());
        }
    }


    public function testClearCampaigns()
    {
        NewCampaignOperation::trucate();
        NewCampaignSystem::trucate();
        NewCampaign::trucate();
        NewOperation::trucate();
        NewSystemNode::trucate();
        NewUserNode::trucate();
        OperationUser::whereNotNull('id')
            ->update([
                'operation_id' => null,
                'user_status_id' => 1,
                'system_id' => null
            ]);
    }

    public function testRunScore()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                "Accept" => "application/json",
                'User-Agent' => 'evestuff.online python9066@gmail.com'
            ])->get("https://628189349fac04c6540639f6.mockapi.io/timers");
            $campaigns = $response->collect();


            foreach ($campaigns as $campaign) {
                $event_type = $campaign['event_type'];
                if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                    $score_changed = false;
                    if ($event_type == 'ihub_defense') {
                        $event_type = 32458;
                    } else {
                        $event_type = 32226;
                    }


                    $id = $campaign['campaign_id'];
                    $old = NewCampaign::where('id', $id)->first();
                    if ($old) {
                        // * Checking if the score has changed
                        if ($campaign['attackers_score'] != $old->attackers_score) {
                            $attackers_score_old = $old->attackers_score;
                            $defenders_score_old = $old->defenders_score;
                            $old->update([
                                'attackers_score_old' => $attackers_score_old,
                                'defenders_score_old' => $defenders_score_old
                            ]);
                            $score_changed = true;
                        }
                    }

                    $time = $campaign['start_time'];
                    $start_time = Helper::fixtime($time);
                    $data = array();
                    $data = array(
                        'attackers_score' => $campaign['attackers_score'],
                        'constellation_id' => $campaign['constellation_id'],
                        'alliance_id' => $campaign['defender_id'],
                        'defenders_score' => $campaign['defender_score'],
                        'event_type' => $event_type,
                        'system_id' => $campaign['solar_system_id'],
                        'start_time' => $start_time,
                        'structure_id' => $campaign['structure_id'],
                        'check' => 1,
                    );

                    NewCampaign::updateOrCreate(['id' => $id], $data);
                    echo $score_changed;
                    // * If Score has changed

                    if ($score_changed) {
                        echo " -  I AM IN   -";
                        $campaign = NewCampaign::where('id', $id)->first();
                        $campaignOperations = NewCampaignOperation::where('campaign_id', $id)->get();
                        $bNode = $campaign->b_node;
                        $rNode = $campaign->r_node;
                        echo $id;
                        $campaignNodes = NewSystemNode::where('campaign_id', $id)->whereIn('node_status', [4, 5])->get();
                        foreach ($campaignNodes as $campaignNode) {
                            $system_id = $campaignNode->system_id;
                            if ($campaignNode->node_status == 4) {
                                $bNode = $bNode + 1;
                                echo "yay add 1 to blue";
                            } else {
                                $rNode = $rNode + 1;
                                echo "yay add 1 to red";
                            }
                            $campaignNode->delete();
                            Broadcasthelper::broadcastsystemSolo($system_id, 7);
                        }

                        $campaign->update(['b_node' => $bNode, 'r_node' => $rNode]);
                        foreach ($campaignOperations as $campaignOperation) {
                            Broadcasthelper::broadcastCampaignSolo($campaign->id, $campaignOperation->operation_id, 4);
                        }
                    }

                    // * Setting everything up for a new campaign
                    if (NewCampaignOperation::where('campaign_id', $id)->count() == 0) {
                        $uuid = Str::uuid();
                        $system = System::where('id', $campaign['solar_system_id'])->first();
                        $systemName = $system->system_name;
                        if ($event_type == 32458) {
                            $type = "Ihub";
                        } else {
                            $type = "TCU";
                        }
                        $title = $systemName . " - " . $type;
                        $newOp = NewOperation::create([
                            'link' => $uuid,
                            'solo' => 1,
                            'status' => 1,
                            'title' => $title,
                        ]);

                        NewCampaignOperation::create([
                            'campaign_id' => $id,
                            'operation_id' => $newOp->id
                        ]);

                        $campaignSystemsIDs = System::where('constellation_id', $campaign['constellation_id'])->pluck('id');
                        foreach ($campaignSystemsIDs as $systemid) {
                            NewCampaignSystem::create([
                                'system_id' => $systemid,
                                'new_campaign_id' => $id
                            ]);
                        }
                    }
                }
            }
        }
    }





    public function corptest()
    {

        WebWay::where('id', '>', 0)->update(['active' => 0]);
        $stations = Station::get();
        $stationSystems = $stations->pluck('system_id');
        $campaigns = Campaign::get();
        $campaginSystems = $campaigns->pluck('system_id');

        $systemIDs = $stationSystems->merge($campaginSystems);
        $systemIDs = $systemIDs->unique();
        $systemIDs = $systemIDs->values();
        WebWay::whereIn('system_id', $systemIDs)->update(['active' => 1]);
        WebWay::where('active', 0)->delete();

        foreach ($systemIDs as $system_id) {
            updateWebwayJob::dispatch($system_id)->onQueue('slow');
        }
    }

    public function popualteCampaignSystemTable()
    {
    }

    public static function getCorpWithNoAlliance()
    {

        $corpID = null;
        $corpTciker = null;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->post("https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en", ["monty"]);

        $returns = $response->collect();
        dd($returns);
        foreach ($returns as $key => $var) {
            if ($key == "corporations") {

                $corpRep = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    "Accept" => "application/json",
                    'User-Agent' => 'evestuff.online python9066@gmail.com'
                ])->get("https://esi.evetech.net/latest/corporations/" . $var[0]['id'] . "/?datasource=tranquility");



                $corpReturn = $corpRep->collect();
                // Corp::create([
                //     'id' => $var[0]['id'],
                //     "name" => $corpReturn["name"],
                //     'ticker' => $corpReturn["ticker"],
                //     'url' => "https://images.evetech.net/Corporation/" . $var[0]['id'] . "_64.png",
                //     'active' => 1
                // ]);

                // $corpID = $var[0]['id'];
                // $corpTciker = $corpReturn["ticker"];
            }
        }

        // $tickerlist = Corp::select(['ticker as text', 'id as value'])->get();


        // return [
        //     'ticklist' => $tickerlist,
        //     'corpID' => $corpID,
        //     'corpTicker' => $corpTciker
        // ];
    }

    public function prequal()
    {

        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a524f35da058742f0defd6fb0db6afc4');
        } else {
            return null;
        }
    }

    public function getSoloOperations()
    {

        $user = Auth::user();
        if ($user->can('super')) {

            return ['operations' => NewOperation::where('solo', 1)
                ->with([
                    'campaign',
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get()];
        } else {
            return null;
        }
    }

    public function horizon()
    {

        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a3bc619080ec6c04c44fffff8cc780de');
        } else {
            return null;
        }
    }

    public function testGetAlliance($id)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/alliances/" . $id . "/?datasource=tranquility");
        $allianceInfo = $response->collect();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/alliances/" . $id . "/corporations/?datasource=tranquility");
        $corpIDs = $response->collect();

        dd($allianceInfo, $corpIDs);
    }


    public function testStationRecords($type)
    {

        $data = Helper::StationRecords($type);
        return ['stations' => $data];
    }


    public function testPull()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ];
        $url = "https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility";
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response = Utils::jsonDecode($response->getBody(), true);
        dd($response);
    }

    public function campaginTest()
    {
        $regionIDs = collect();
        $regions = NewOperation::where('solo', 1)->with('campaign.constellation.region')->get();
        foreach ($regions as $r) {
            $regionIDs->push($r->campaign[0]->constellation->region->id);
        }

        $uRegionIDs = $regionIDs->unique();
        $regionList = Region::whereIn('id', $uRegionIDs)->select(['id as value', 'region_name as text'])->orderBy('region_name', 'asc')->get();
        return ['regionList' => $regionList];
    }

    public function campaginListTest()
    {
        if (Auth::user()->can('super')) {

            $list = NewOperation::where('solo', 1)
                ->with([
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.structure:id,age'
                ])
                ->get();
            return ['list' => $list];
        }
    }

    public function corptest2()
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        /*
        send = [
            startSystem => start system get from env (1dq)
            endStstem => $system_id
        ]
       return =  api code to request too webway repos $response will be:
            [
                link: UUID of the saved route
                jumps: number of jumps from 1dq ( ID got from env file) to target system
                link_p: UUID of the saved route (with permissions)
                jumps_p: number of jumps from 1dq ( ID got from env file) to target system (with permissions)
            ]
        */

        $startSystem = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
        $webwayURL = env('WEBWAY_URL', ($variables && array_key_exists('WEBWAY_URL', $variables)) ? $variables['WEBWAY_URL'] : null);
        $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);

        $data = [
            'startSystem' => $startSystem,
            'endSystem' => 30000142
        ];

        Http::withToken($webwayToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                "Accept" => "application/json",
                'User-Agent' => 'evestuff.online python9066@gmail.com'
            ])->post($webwayURL, $data);
    }


    public function notifications(Request $request)
    {

        testNote::create(['text' => $request]);
        Notifications::test($request, 1);
    }

    public function rc(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {

            dd($input);
        }
    }

    public function userinfo()
    {
        $user = Auth::user()->name;
        $id = Auth::id();
        $current = User::find($id);
        dd($user, $id, $current);
    }


    public function test($id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $url = "https://recon.gnf.lt/api/structure/" . $id;
        // $dance = env('RECON_TOKEN', "DANCE");
        $dance = env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2');
        // $dance2 = env('RECON_USER', 'DANCE2');
        $dance2 = env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2');

        $client = new Client();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data = "Error, Structure Not Found") {
            // echo "NO STATION";
        } else {
            // echo $dance . " - " . $dance2;
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
        }
    }
}
