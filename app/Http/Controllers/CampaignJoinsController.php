<?php

namespace App\Http\Controllers;

use App\Models\CampaignJoin;
use App\Models\CampaignRecords;
use Illuminate\Http\Request;

class CampaignJoinsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $pull = CampaignJoin::all();
        foreach ($pull as $pull) {
            $camp = CampaignRecords::where('id', $pull['campaign_id'])->get();
            $count = $camp->count();
            if($count != 0){
            foreach ($camp as $camp) {
                $data = [];
                $data = [
                    "text" => $camp['system']. " - ". $camp['item_name'],
                    "custom_campaign_id" => $pull['custom_campaign_id'],
                    "color" => $camp['color']
                ];
            }
            array_push($list, $data);
        }}
        return ["value" => $list];
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

    public function campaignSystems($id)
    {
        $list = [];
        $pull = CampaignJoin::where('custom_campaign_id', $id)->with('campaign')->get();
        dd($pull);
        foreach ($pull as $pull) {
            $camp = CampaignRecords::where('id', $pull)->get();
            $count = $camp->count();
            if($count != 0){
            foreach ($camp as $camp) {
                $data = [];
                $data = [
                    "id" => $camp['system_id'],
                    "system_name" => $camp['system']
                ];
            }
            array_push($list, $data);
        }}
        return ["systems" => $list];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = [];
        $pull = CampaignJoin::where('custom_campaign_id', $id)->get()->pluck('campaign_id');
        foreach ($pull as $pull) {
            $camp = CampaignRecords::where('id', $pull)->get();
            $count = $camp->count();
            if($count != 0){
            foreach ($camp as $camp) {
                $data = [];
                $data = [
                    "text" => $camp['system']. " - ". $camp['item_name']
                ];
            }
            array_push($list, $data);
        }}
        return ["value" => $list];
    }

    public function list($id)
    {
        $pull = CampaignJoin::where('custom_campaign_id', $id)->get()->pluck('campaign_id');

        return ["value" => $pull];
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
