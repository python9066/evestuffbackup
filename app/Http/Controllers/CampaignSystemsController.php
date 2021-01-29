<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignUser;
use App\Models\NodeJoin;
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

        $system = CampaignSystem::create($request->all());
        $system->update(['input_time' => now()]);
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
        $nodejoin = NodeJoin::where('campaign_id', $id);
        if ($nodejoin->count < 1) {
            CampaignSystem::where('id', $id)->update($request->all());
            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
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

        $users = CampaignUser::where('campaign_system_id', $id)->get();
        foreach ($users as $user) {
            $user->update(['campaign_system_id' => null, 'status_id' => 3]);
        }
        NodeJoin::where('campaign_system_id', $id)->delete();
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

        $systems = CampaignSystem::where('system_id', $sysid)
            ->where('campaign_id', $campid)
            ->where('end_time', "!=", null)
            ->where('end_time', ">", now())
            ->where('campaign_system_status_id', '!=', 4)
            ->where('campaign_system_status_id', '!=', 5)
            ->where('campaign_system_status_id', '!=', 10)
            ->get();

        // dd($systems->count());
        if ($systems->count() != 0) {
            foreach ($systems as $system) {
                $time_passed = strtotime(now()) - strtotime($system->input_time);
                $base_time = $system->base_time - $time_passed;
                $time_left = $base_time / ($request->newTidi / 100);
                $end_time = now()->modify("+ " . round($time_left) . " seconds");
                $system->update(['end_time' => $end_time, 'input_time' => now(), 'base_time' => $base_time]);
                $system->save();
            }
        }
        CampaignSolaSystem::where('id', $request->solaID)->update(['tidi' => $request->newTidi]);


        $flag = collect([
            'flag' => 9,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function tidimulti(Request $request, $sysid, $campid)
    {

        $systems = CampaignSystem::where('system_id', $sysid)
            ->where('custom_campaign_id', $campid)
            ->where('end_time', "!=", null)
            ->where('end_time', ">", now())
            ->where('campaign_system_status_id', '!=', 4)
            ->where('campaign_system_status_id', '!=', 5)
            ->where('campaign_system_status_id', '!=', 10)
            ->get();

        // dd($systems->count());
        if ($systems->count() != 0) {
            foreach ($systems as $system) {
                $time_passed = strtotime(now()) - strtotime($system->input_time);
                $base_time = $system->base_time - $time_passed;
                $time_left = $base_time / ($request->newTidi / 100);
                $end_time = now()->modify("+ " . round($time_left) . " seconds");
                $system->update(['end_time' => $end_time, 'input_time' => now(), 'base_time' => $base_time]);
                $system->save();
            }
        }
        CampaignSolaSystem::where('id', $request->solaID)->update(['tidi' => $request->newTidi]);


        $flag = collect([
            'flag' => 9,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }
}
