<?php

namespace App\Http\Controllers;

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

            $charname = $join->campaignUser->char_name;
            $siteid = $join->campaignUser->site_id;
            $mainname = User::where('id', $siteid)->value('name');
            $ship = $join->campaignUser->ship;
            $link = $join->campaignUser->link;
            $statusName = CampaignSystemStatus::where('id', $join->campaign_system_status_id);



            dd($charname, $siteid, $mainname, $ship, $link, $statusName);
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
