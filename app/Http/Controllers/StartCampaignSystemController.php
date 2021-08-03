<?php

namespace App\Http\Controllers;

use App\Events\StartCampaignSystemUpdate;
use App\Models\Auth;
use App\Models\CampaignUser;
use App\Models\StartCampaigns;
use App\Models\StartCampaignSystemRecords;
use App\Models\StartCampaignSystems;
use Illuminate\Http\Request;

class StartCampaignSystemController extends Controller
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
    public function update(Request $request, $id, $campid)
    {


        CampaignUser::where('id', $request->user_id)->update(['campaign_system_id' => $request->sys]);
        StartCampaignSystems::find($id)->update(['campaign_user_id' => $request->user_id]);


        $message = StartCampaignSystemRecords::where('id', $id)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid
        ]);
        broadcast(new StartCampaignSystemUpdate($flag))->toOthers();
    }

    public function removeChar($id, $char, $campid)
    {
        CampaignUser::find($char)->update(['campaign_id' => null, 'campaign_system_id' => null]);
        StartCampaignSystems::find($id)->update(['campaign_user_id' => null]);

        $message = StartCampaignSystemRecords::where('id', $id)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid
        ]);
        broadcast(new StartCampaignSystemUpdate($flag))->toOthers();
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
