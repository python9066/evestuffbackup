<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
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
        $list = [];
        $pull = StartCampaigns::all();
        foreach ($pull as $pull) {
            $data = [];
            $data = [
                'id' => $pull['id'],
                'name' => $pull['name']
            ];
            array_push($list, $data);
        };

        return ['campaigns' => $list];
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

                StartCampaignSystems::create(['system_id' => $sola['id'], 'start_campaign_id' => $campid, 'constellation_id' => $data]);
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
        $s = StartCampaigns::where('id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = StartCampaignJoins::where('start_campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = StartCampaignSystems::where('start_campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = CampaignSystemUsers::where('campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $c = CampaignUser::where('campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->update([
                'campaign_id' => null,
                'campaign_system_id' => null,
                'status_id' => 1
            ]);
        }
    }
}
