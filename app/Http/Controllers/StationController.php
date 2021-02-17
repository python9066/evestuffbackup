<?php

namespace App\Http\Controllers;


use App\Events\StationCoreUpdate;
use App\Events\StationNotificationUpdate;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationRecords;
use App\Models\System;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;

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
        $url = "https://dev.scouts.scopeh.co.uk/api/task/add";
        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'token' =>  env('RECON_TASK_TOKEN', "DANCE")
        ];

        $body = [
            'system' => $request->system_name,
            'task' => $request->structure_name
        ];
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $body,
            'http_errors' => false
        ]);
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
