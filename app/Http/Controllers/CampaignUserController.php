<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemUpdate;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use Illuminate\Http\Request;

class CampaignUserController extends Controller
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
    public function store(Request $request, $campid)
    {

        CampaignUser::create($request->all());
        $flag = collect([
            'flag' => 1,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
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
        CampaignUser::find($id)->update($request->all());
        $flag = collect([
            'flag' => 1,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid, $siteid)
    {

        CampaignUser::destroy($id);
        CampaignSystemUsers::where('user_id', $siteid)->delete();

        if (CampaignSystem::where('campaign_user_id', $id)->count() != 0) {
            CampaignSystem::where('campaign_user_id', $id)->update(['campaign_user_id'=>null]);
        }
        // dd($remove,$siteid);
        $flag = collect([
            'flag' => 1,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }
}
