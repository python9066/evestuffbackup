<?php

namespace App\Http\Controllers;

use App\Models\StartCampaignJoins;
use App\Models\StartCampaigns;
use App\Models\StartCampaignSystems;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StartCampaignController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid, $name)
    {
        $data = $request->all();
        $user_id = Auth::id();
        // dd($data);
        StartCampaigns::create(['id' => $campid, 'name' => $name, 'user_id' => $user_id]);
        foreach ($data as $data) {
            // dd($data);
            StartCampaignJoins::create(['start_campaign_id' => $campid, 'constellation_id' => $data]);
            $solas = System::where('constellation_id', $data)->get();
            foreach ($solas as $sola) {

                StartCampaignSystems::create(['system_id' => $sola['id'], 'start_campaign_id' => [$campid], 'constellation_id' => $sola['contellation_id']]);
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
