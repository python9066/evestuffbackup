<?php

namespace App\Http\Controllers;

use App\Events\SoloOperationUpdate;
use App\Models\NewCampaign;
use App\Models\NewOperation;
use Illuminate\Http\Request;

class NewCampaignsController extends Controller
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

    public function campaignsList()
    {
        $data = [];
        $pull = NewCampaign::whereIn('status_id', [1, 2, 5])->with(['system.region', 'system.constellation', 'alliance'])->orderBy('start_time', 'asc')->get();
        foreach ($pull as $pull) {
            $data1 = [];
            if ($pull->event_type == 32226) {
                $eventType = "TCU";
            } else {
                $eventType = "Ihub";
            }
            $systemName = $pull->system->system_name;
            $regionName = $pull->system->region->region_name;
            $constellationName = $pull->system->constellation->constellation_name;
            $allianceName = $pull->alliance->name;

            $text = $regionName . " - " . $constellationName . " - " . $systemName . " - " . $allianceName . " - " . $eventType . " - " . $pull->start_time;


            $data1 = [
                "text" => $text,
                'value' => $pull['id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['campaignslist' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $n = NewCampaign::find($id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        $flag = collect([
            'flag' => 4,
            'id' => $id
        ]);
        broadcast(new SoloOperationUpdate($flag));
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
