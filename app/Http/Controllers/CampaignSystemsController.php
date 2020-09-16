<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Events\CampaiganSystemUpdate;
use App\Models\CampaignUser;
use Illuminate\Http\Request;

class CampaignSystemsController extends Controller
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
        return CampaignSystem::create($request->all());
        $flag = collect([
            'flag' => 2,
            'id' => $request->campaign_id
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
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
        CampaignSystem::find($id)->update($request->all());
        $data = CampaignSystem::find($id)->first();
        $flag = collect([
            'flag' => 2,
            'id' => $request->campaign_id
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = CampaignSystem::find($id)->first();
        $flag = collect([
            'flag' => 2,
            'id' => $data->campaign_id
        ]);
        CampaignSystem::destroy($id);
        CampaignUser::where('campaign_system_id',$id)->update(['campaign_system_id' => null]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
        $flag =null;
        $flag = collect([
            'flag' => 1,
            'id' => $data->campaign_id
        ]);
    }
}
