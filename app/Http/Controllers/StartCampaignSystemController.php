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
        $count = CampaignUser::where(['site_id' => $request->user_id])->count();
        if ($count != null) {
            CampaignUser::first('site_id', $request->user_id)->update(['campaign_id' => $id, 'campaign_system_id' => $request->sys]);
            $char = CampaignUser::where('site_id', $request->user_id)->first();
        } else {
            $char =  CampaignUser::create(['site_id' => Auth::id(), 'campaign_id' => $campid, 'campaign_system_id' => $request->sys, 'char_name' => Auth::user()])->get();
        }
        StartCampaignSystems::find($id)->update(['campaign_user_id' => $char->id]);


        $message = StartCampaignSystemRecords::where('id',)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid
        ]);
        dd($flag);
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
