<?php

namespace App\Http\Controllers;

use App\Events\ChillStationCoreUpdate;
use App\Events\RcMoveDelete;
use App\Events\RcMoveUpdate;
use App\Events\RcSheetUpdate;
use App\Events\StationAttackMessageUpdate;
use App\Events\StationCoreUpdate;
use App\Events\StationDeadCoord;
use App\Events\StationMessageUpdate;
use App\Events\StationNotificationDelete;
use App\Events\StationNotificationNew;
use App\Events\StationNotificationUpdate;
use App\Events\StationUpdateCoord;
use App\Models\Corp;
use App\Models\Item;
use App\Models\Logging;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationRecords;
use App\Models\StationStatus;
use App\Models\System;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function loadStationData()
    {
        $coreData = [];
        $stations = Station::all();
        foreach ($stations as $station) {
            if ($station->r_cored == "Yes") {
                $core = "Yes";
            } else {
                $core = "No";
            }

            $taskFlag = System::where('id', $station->system_id)->value('task_flag');

            $data1 = [
                "task_flag" => $taskFlag,
                "station_id" => $station->id,
                "cored" => $core,
            ];

            array_push($coreData, $data1);
        }

        $items = [];
        $joins = StationItemJoin::all();
        foreach ($joins as $join) {
            $name = StationItems::where('id', $join->station_item_id)->first();
            $data = [
                "station_id" => $join->station_id,
                "item_name" => $name->item_name,
                "item_id" => $name->id
            ];
            array_push($items, $data);
        }



        return [
            'cores' => $coreData,
            'fit' => Station::where('r_hash', '!=', null)->get(),
            'items' => $items
        ];
    }


    public function reconRegionPull()
    {
        Artisan::call('update:reconstationsbyregion');
    }

    public function taskRequest(Request $request)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        $message = [
            'station_id' => $request->station_id,
            'task_flag' => 1
        ];

        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationCoreUpdate($flag));

        System::where('id', $request->system_id)->update(['task_flag' => 1]);
        $url = "https://recon.gnf.lt/api/task/add";
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];

        $body = [
            'system' => $request->system_name,
            'task' => $request->structure_name,
            'username' => $request->username
        ];
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $body,
            'http_errors' => false
        ]);
    }


    public static function reconPullbyname(Request $request)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);

        $name = preg_replace("/\([^\)]+\)(\R|$)/", "$1", $request->stationName);
        $name = rtrim($name);
        // dd($name);
        $url = "https://recon.gnf.lt/api/structure/" . $name;

        $client = new GuzzleHttpClient();
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

        // dd($response);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($response->getStatusCode() == 200) {

            if ($stationdata == "Error, Structure Not Found") {
                $stationCheck = station::where('name', $name)->get();
                $check = $stationCheck->count();
                if ($check > null) {
                    $data = [];
                } else {
                    $data = [
                        'state' => 2,
                        'station_name' => $name
                    ];
                    return $data;
                }
            } else {

                $checkifthere = Station::find($stationdata['str_structure_id']);
                $showMain = 0;
                $showChill = 0;
                $showRcMove = 0;
                if ($checkifthere) {
                    $showMain = $checkifthere->show_on_main;
                    $showChill = $checkifthere->show_on_chill;
                    $showRcMove =  $checkifthere->show_on_rc_move;
                    if ($request->show == 1) {
                        $showMain = 1;
                    }
                    if ($request->show == 2) {
                        $showChill = 1;
                    }
                    if ($request->show == 3) {
                        $showRcMove = 1;
                    }
                }



                Station::updateOrCreate(['id' => $stationdata['str_structure_id']], [
                    'name' => $stationdata['str_name'],
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'item_id' => $stationdata['str_type_id'],
                    'text' => null,
                    'station_status_id' => 10,
                    'user_id' => null,
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
                    'r_updated_at' => $stationdata['updated_at'],
                    'r_fitted' => $stationdata['str_has_no_fitting'],
                    'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                    'r_hyasyoda' => $stationdata['str_hyasyoda'],
                    'r_invention' => $stationdata['str_invention'],
                    'r_manufacturing' => $stationdata['str_manufacturing'],
                    'r_research' => $stationdata['str_research'],
                    'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                    'r_biochemical' => $stationdata['str_biochemical'],
                    'r_hybrid' => $stationdata['str_hybrid'],
                    'r_moon_drilling' => $stationdata['str_moon_drilling'],
                    'r_reprocessing' => $stationdata['str_reprocessing'],
                    'r_point_defense' => $stationdata['str_point_defense'],
                    'r_dooms_day' => $stationdata['str_dooms_day'],
                    'r_guide_bombs' => $stationdata['str_guide_bombs'],
                    'r_anti_cap' => $stationdata['str_anti_cap'],
                    'r_anti_subcap' => $stationdata['str_anti_subcap'],
                    'r_t2_rigged' => $stationdata['str_t2_rigged'],
                    'r_cloning' => $stationdata['str_cloning'],
                    'r_composite' => $stationdata['str_composite'],
                    'r_cored' => $stationdata['str_cored'],
                    'show_on_main' => $showMain,
                    'show_on_chill' => $showChill,
                    'show_on_rc_move' => $showRcMove,
                    'added_by_user_id' => Auth::id(),
                    'added_from_recon' => 1
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    };
                }

                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $item = Item::where('id', $stationdata['str_type_id'])->first();
                $system = System::where('id', $stationdata['str_system_id'])->first();
                $data = [];
                $data = [
                    'state' => 3,
                    'station_id' => $stationdata['str_structure_id'],
                    'station_name' => $stationdata['str_name'],
                    'structure_name' => $item->item_name,
                    'system_name' => $system->system_name,
                    'corp_ticker' => $corp->ticker,
                ];
                return $data;
            }
        } else {

            $stationCheck = station::where('name', $name)->first();;
            if ($stationCheck) {
                $station = StationRecords::where('id', $stationCheck->id)->first();
                return $data = [
                    'state' => 3,
                    'station_id' => $station->id,
                    'station_name' => $station->name,
                    'structure_name' => $station->item_name,
                    'system_name' => $station->system_name,
                    'corp_ticker' => $station->corp_ticker,
                ];
            } else {
                $data = [
                    'state' => 2,
                    'station_name' => $name
                ];
                return $data;
            }
        };
    }



    public static function editStationNameReconCheck(Request $request, $id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);

        $name = preg_replace("/\([^\)]+\)(\R|$)/", "$1", $request->stationName);
        $name = rtrim($name);
        // dd($name);
        $url = "https://recon.gnf.lt/api/structure/" . $name;

        $client = new GuzzleHttpClient();
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

        // dd($response);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($response->getStatusCode() == 200) {

            if ($stationdata == "Error, Structure Not Found") {

                Station::where('id', $id)->update(['name' => $name, 'added_from_recon' => 0]);
            } else {

                $checkifthere = Station::find($stationdata['str_structure_id']);
                $showMain = 0;
                $showChill = 0;
                $showRcMove = 1;
                if ($checkifthere) {
                    $showMain = $checkifthere->show_on_main;
                    $showChill = $checkifthere->show_on_chill;
                    $showRcMove =  $checkifthere->show_on_rc_move;
                    if ($request->show == 1) {
                        $showMain = 1;
                    }
                    if ($request->show == 2) {
                        $showChill = 1;
                    }
                    if ($request->show == 3) {
                        $showRcMove = 1;
                    }
                }



                Station::updateOrCreate(['id' => $stationdata['str_structure_id']], [
                    'name' => $stationdata['str_name'],
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'item_id' => $stationdata['str_type_id'],
                    'text' => null,
                    'station_status_id' => 10,
                    'user_id' => null,
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
                    'r_updated_at' => $stationdata['updated_at'],
                    'r_fitted' => $stationdata['str_has_no_fitting'],
                    'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                    'r_hyasyoda' => $stationdata['str_hyasyoda'],
                    'r_invention' => $stationdata['str_invention'],
                    'r_manufacturing' => $stationdata['str_manufacturing'],
                    'r_research' => $stationdata['str_research'],
                    'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                    'r_biochemical' => $stationdata['str_biochemical'],
                    'r_hybrid' => $stationdata['str_hybrid'],
                    'r_moon_drilling' => $stationdata['str_moon_drilling'],
                    'r_reprocessing' => $stationdata['str_reprocessing'],
                    'r_point_defense' => $stationdata['str_point_defense'],
                    'r_dooms_day' => $stationdata['str_dooms_day'],
                    'r_guide_bombs' => $stationdata['str_guide_bombs'],
                    'r_anti_cap' => $stationdata['str_anti_cap'],
                    'r_anti_subcap' => $stationdata['str_anti_subcap'],
                    'r_t2_rigged' => $stationdata['str_t2_rigged'],
                    'r_cloning' => $stationdata['str_cloning'],
                    'r_composite' => $stationdata['str_composite'],
                    'r_cored' => $stationdata['str_cored'],
                    'show_on_main' => $showMain,
                    'show_on_chill' => $showChill,
                    'show_on_rc_move' => $showRcMove,
                    'added_from_recon' => 1
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    };
                };
            }
        } else {

            Station::where('id', $id)->update(['name' => $name, 'added_from_recon' => 0]);
        };
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request#
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Station::where('added_from_recon', 0)->max('id');
        if ($id == null) {
            $id = 1;
        } else {
            $id = $id + 1;
        }
        $new = Station::Create($request->all());
        $now = now();
        $noteText = $now . " -  Submitted By :" . Auth::user()->name;
        $new->update(['id' => $id, 'added_by_user_id' => Auth::id(), "notes" => $noteText]);
        $message = StationRecords::where('id', $new->id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationNew($flag));
        broadcast(new RcMoveUpdate($flag));
        $text = Auth::user()->name . " Added " . $request->name . " At " . now();
        $logNew = Logging::Create(['structure_id' => $message->id, 'user_id' => Auth::id(), 'logging_type_id' => 17, 'text' => $text]);
    }

    public function updateAttackMessage(Request $request, $id)
    {
        // dd($request->notes);

        Station::where('id', $id)->update($request->all());

        $message = StationRecords::where('id', $id)->first();
        if ($message->under_attack == 0) {
            $type = 2;
        } else {
            $type = 1;
        }
        $flag = collect([
            'type' => $type,
            'message' => $message,
            'id' => $id
        ]);

        // dd($request, $id, $flag);
        broadcast(new StationAttackMessageUpdate($flag))->toOthers();
    }

    public function updateMessage(Request $request, $id)
    {
        // dd($request->notes);

        Station::where('id', $id)->update($request->all());

        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $id
        ]);

        // dd($request, $id, $flag);
        broadcast(new StationMessageUpdate($flag))->toOthers();
    }


    public function rcMoveDone($id)
    {
        $statusID = Station::where('id', $id)->value('station_status_id');
        Station::where('id', $id)->update(['show_on_rc_move' => 0, 'show_on_rc' => 1, 'station_status_id' => $statusID, 'notes' => null, 'timer_image_link' => null]);
        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new RcMoveUpdate($flag));

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $oldStatusID = Station::where('id', $id)->pluck('station_status_id');
        $oldStatusName = StationStatus::where('id', $oldStatusID)->pluck('name');

        $RCmessage = RcStationRecords::where('id', $id)->first();
        if ($RCmessage) {
            $RCmessageSend = [
                'id' => $RCmessage->id,
                'show_on_rc' => 0
            ];
            $flag = collect([
                'message' => $RCmessageSend,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }



        $newStatusID = $request->station_status_id;
        $newStatusName = StationStatus::where('id', $newStatusID)->pluck('name');
        $new = Station::find($id)->update($request->all());
        $now = now();
        $noteText = $now . " -  Submitted By :" . Auth::user()->name;
        Station::find($id)->update(['added_by_user_id' => Auth::id(), "notes" => $noteText, "rc_id" => null, "rc_fc_id" => null, "rc_gsol_id" => null, "rc_recon_id" => null,]);
        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationUpdate($flag));
        broadcast(new StationUpdateCoord($flag));

        $text = Auth::user()->name . " Changed the status from " . $oldStatusName . " to " . $newStatusName . ' at ' . now();
        $logNew = Logging::Create(['structure_id' => $message->id, 'user_id' => Auth::id(), 'logging_type_id' => 18, 'text' => $text]);
    }

    public function editUpdate(Request $request, $id)
    {
        // dd($id);
        $now = now();

        $RCmessage = RcStationRecords::where('id', $id)->first();
        $RCmessageSend = [
            'id' => $RCmessage->id,
            'show_on_rc' => 0
        ];
        $flag = collect([
            'message' => $RCmessageSend,
        ]);
        broadcast(new RcSheetUpdate($flag));

        $oldStation = Station::where('id', $id)->first();
        $oldStatus = StationStatus::where('id', $oldStation->station_status_id)->value('name');


        Station::find($id)->update($request->all());
        $newStation = Station::where('id', $id)->first();
        $newStatus = StationStatus::where('id', $newStation->station_status_id)->value('name');

        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationUpdate($flag));
        broadcast(new StationUpdateCoord($flag));
        broadcast(new RcMoveUpdate($flag));




        if ($request->station_status_id != $oldStation->station_status_id) {
            $text = Auth::user()->name .  "changed the Status from " . $oldStatus . " to " . $newStatus . " at " . now();
            Logging::create(['station_id' => $id, 'user_id' => Auth::id(), 'logging_type_id' => 18, 'text' => $text]);
        }

        if ($request->out_time != $oldStation->out_time) {
            $text = Auth::user()->name .  "changed the " . $oldStation->out_time . " to " . $request->out_time . " at " . now();
            Logging::create(['station_id' => $id, 'user_id' => Auth::id(), 'logging_type_id' => 18, 'text' => $text]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Station::where('id', $id)->delete();
        StationItemJoin::where('station_id', $id)->delete();
        $flag = collect([
            'id' => $id
        ]);
        broadcast(new RcMoveDelete($flag));
        broadcast(new StationNotificationDelete($flag));
        broadcast(new StationDeadCoord($flag));
    }

    public function softDestroy($id)
    {

        $now = now();
        $noteText = $now . " -  Submitted By :" . Auth::user()->name . " Station Destoryed";
        $stationName = Station::find($id)->value('name');

        $RCmessage = RcStationRecords::where('id', $id)->first();
        $RCmessageSend = [
            'id' => $RCmessage->id,
            'show_on_rc' => 0
        ];
        $flag = collect([
            'message' => $RCmessageSend,
        ]);
        broadcast(new RcSheetUpdate($flag));


        Station::where('id', $id)->update(['show_on_rc' => 0, 'show_on_coord' => 1, "notes" => $noteText, 'station_status_id' => 7, "rc_id" => null, "rc_fc_id" => null, "rc_gsol_id" => null, "rc_recon_id" => null]);

        $oldStatusID = Station::where('id', $id)->pluck('station_status_id')->first();
        $oldStatusName = StationStatus::where('id', $oldStatusID)->pluck('name')->first();
        $newStatusID = 7;
        $newStatusName = StationStatus::where('id', $newStatusID)->pluck('name')->first();

        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationUpdate($flag));
        broadcast(new StationUpdateCoord($flag));





        $text = Auth::user()->name . " Changed the status from " . $oldStatusName . " to " . $newStatusName . ' for ' . $stationName . ' at ' . now();
        $logNew = Logging::Create(['structure_id' => $message->id, 'user_id' => Auth::id(), 'logging_type_id' => 18, 'text' => $text]);
    }
}
