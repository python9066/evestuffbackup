<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignSolaSystem;
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
     * Store a newlfwfwy created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {

        CampaignSystem::create($request->all());
        $flag = collect([
            'flag' => 2,
            'id' => $campid,
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
        // dd($request->notes);
        CampaignSystem::where('id', $id)->update($request->all());
        $flag = collect([
            'flag' => 2,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function removechar(Request $request, $campid)
    {
        $node = CampaignSystem::where('campaign_id', $request->campaign_id)
            ->where('system_id', $request->system_id)
            ->where('campaign_user_id', $request->campaign_user_id)->first();



        if ($node != null) {
            $node->update(['campaign_user_id' =>  null]);
            $node->save();
            $test = CampaignSystem::where('campaign_id', $request->campaign_id)
                ->where('system_id', $request->system_id)->get();



            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
    }

    public function movechar(Request $request, $campid)
    {

        $node = CampaignSystem::where('campaign_user_id', $request->id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $test = CampaignSystem::where('campaign_id', $request->campaign_id)
                ->where('system_id', $request->system_id)->get();

            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid)
    {

        CampaignSystem::destroy($id);
        $flag = collect([
            'flag' => 3,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function checkAddChar($campid)
    {


        $flag = collect([
            'flag' => 5,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function kickUser(Request $request, $campid)
    {
        $flag = collect([
            'flag' => 6,
            'id' => $campid,
            'user_id' => $request['user_id']
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function finishCampaign($campid)
    {
        $flag = collect([
            'flag' => 7,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function tidi(Request $request, $sysid, $campid)
    {

        $systems = CampaignSystem::where('system_id', $sysid)->where('campaign_id', $campid)->where('end_time', "!=", null)->get();


        if ($systems->count() != 0) {
            foreach ($systems as $system) {
                $time_left = strtotime($system->end_time) - strtotime(now());
                $time_left = $time_left * ($request->oldTidi / 100);
                $end_time = now()->modify("+ " . $time_left . " seconds");
                dd($end_time);
            }
        }

        // $flag = collect([
        //     'flag' => 7,
        //     'id' => $campid,
        // ]);
        // broadcast(new CampaignSystemUpdate($flag));
    }
}
