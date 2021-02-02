<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Events\CampaignSystemUpdate;
use App\Models\Campaign;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignSystemStatus;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\NodeJoin;
use App\Models\User;
use Illuminate\Http\Request;

class CampaignSystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function load(Request $request)
    {
        $campid = Campaign::where('link', $request['campaign_id'])->value('id');
        $userid = $request['user_id'];
        $dataSola = [];
        $pull = CampaignSolaSystem::where('campaign_id', $campid)->get();
        foreach ($pull as $pull) {
            $checker_name = null;
            $supervier_name = null;
            if ($pull['last_checked_user_id'] != null) {
                $checker_name = User::where('id', $pull['last_checked_user_id']->value('name'));
            }

            if ($pull['supervisor_id'] != null) {
                $supervier_name = User::where('id', $pull['supervisor_id'])->value('name');
            }

            $dataSola1 = [];
            $dataSola1 = [
                "id" => $pull['id'],
                "system_id" => $pull['system_id'],
                "campaign_id" => $pull['campaign_id'],
                "supervisor_id" => $pull['supervisor_id'],
                "supervier_user_name" => $supervier_name,
                "last_checked_user_id" => $pull['last_checked_user_id'],
                "last_checked_user_name" => $checker_name,
                "last_checked" => $pull['last_checked'],
                "tidi" => $pull['tidi'],
            ];
            array_push($dataSola, $dataSola1);
        }
        $pull = [];
        $nodeJoin = [];
        $joins = NodeJoin::where('campaign_id', $campid)->get();
        if ($joins->count() > 0) {
            foreach ($joins as $join) {

                $pull = [
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
                    'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->system_id)->value('id'),
                    'campaign_id' => $campid
                ];
                array_push($nodeJoin, $pull);
            }
        }

        return [
            'users' => CampaignUserRecords::where('campaign_id', $campid)->get(),
            'sola' => $dataSola,
            'nodejoin' => $nodeJoin,
            'systems' => CampaignSystemRecords::all(),
            'usersbyid' => CampaignUserRecords::where('site_id', $userid)->get()
        ];
    }

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
            'flag' => 3,
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
