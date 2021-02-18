<?php

namespace App\Http\Controllers;


use App\Events\StationCoreUpdate;
use App\Events\StationNotificationUpdate;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Item;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationRecords;
use App\Models\System;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;

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

    public function taskRequest(Request $request)
    {

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
            'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'token' =>  env('RECON_TASK_TOKEN', "DANCE")
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


    public static function reconPullbyname($request)
    {
        $url = "https://recon.gnf.lt/api/structure/" . $request->stationName;

        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'token' =>  env('RECON_TOKEN', "DANCE")

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false
        ]);
        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($stationdata == "Error, Structure Not Found") {
            $data = [
                'state' => 2,
                'station_name' => $request->stationName
            ];
            return $data;
        } else {

            Station::updateOrCreate([$stationdata['str_structure_id']], [
                'name' => $stationdata['str_name'],
                'system_id' => $stationdata['str_system_id'],
                'corp_id' => $stationdata['str_owner_corporation_id'],
                'item_id' => $stationdata['str_type_id'],
                'text' => null,
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
                'r_cored' => $stationdata['str_cored']
            ]);
        };
        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
        $data = [];
        $data = [
            'state' => 3,
            'station_name' => $stationdata['str_name'],
            'system_name' => System::where('id', $stationdata['str_system_id'])->first()->pluck('system_name'),
            'alliance_name' => Alliance::where('id', $corp->id)->first()->plcuk('name'),
            'corp_name' => $corp->name,
            'type' => Item::where('id', $stationdata['str_type_id'])->first()->plunk('item_name'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        Station::find($id)->update($request->all());
        $message = StationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
