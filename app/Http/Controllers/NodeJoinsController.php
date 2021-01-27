<?php

namespace App\Http\Controllers;

use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystemStatus;
use App\Models\NodeJoin;
use App\Models\User;
use Illuminate\Http\Request;

class NodeJoinsController extends Controller
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

    public function tableindex()
    {
        $joins = NodeJoin::all();
        foreach ($joins as $join) {

            // $id = $join->id;
            // $campaign_system_id = $join->campaign_system_id;
            // $campaign_user_id = $join->campaign_user_id;
            // $charname = $join->campaignUser->char_name;
            // $siteid = $join->campaignUser->site_id;
            // $mainname = User::where('id', $siteid)->value('name');
            // $ship = $join->campaignUser->ship;
            // $link = intval($join->campaignUser->link);
            // $campaign_system_status_id = $join->campaign_system_status_id;
            // $statusName = CampaignSystemStatus::where('id', $join->campaign_system_status_id)->value('name');
            // $campaign_sola_system = CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->system_id)->value('id');


            $data = [
                'id' => $join->id,
                'campaign_system_id' => $join->campaign_system_id,
                'campaign_user_id' => $join->campaign_user_id,
                'charname' => $join->campaignUser->char_name,
                'siteid' => $join->campaignUser->site_id,
                'mainname' => User::where('id', $join->campaignUser->site_id)->value('name'),
                'ship' => $join->campaignUser->ship,
                'link' => intval($join->campaignUser->link),
                'campaign_system_status_id' => intval($join->campaign_system_status_id),
                'statusName' => CampaignSystemStatus::where('id', $join->campaign_system_status_id)->value('name'),
                'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->system_id)->value('id')
            ];



            dd($data);
        };
        // echo '<pre>';
        // print_r($join);
        // echo '</pre>';

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
