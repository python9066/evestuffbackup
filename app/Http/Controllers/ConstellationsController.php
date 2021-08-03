<?php

namespace App\Http\Controllers;

use App\Models\Constellation;
use App\Models\StartCampaignJoins;
use App\Models\StartCampaigns;
use App\Models\StartCampaignSystems;
use App\Models\System;
use Illuminate\Http\Request;

class ConstellationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Constellation::all();
        return ['constellationlist' => $data];
    }

    public function constellationlist()
    {
        $data = [];
        $start = Constellation::all();
        $pull = $start->sortBy('constellation_name');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                "text" => $pull['constellation_name'],
                'value' => $pull['id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['constellationlist' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, $campid, $name)
    {
        $data = $request->all();
        // dd($data);
        StartCampaigns::create(['id' => $campid, 'name' => $name]);
        foreach ($data as $data) {
            // dd($data);
            StartCampaignJoins::create(['start_campaign_id' => $campid, 'constellation_id' => $data]);
            $solas = System::where('constellation_id', $data)->get();
            foreach ($solas as $sola) {

                StartCampaignSystems::create(['system_id' => $sola['system_id'], 'start_campaign_id' => $campid]);
            }
        }
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
        //
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
