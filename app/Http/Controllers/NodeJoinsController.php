<?php

namespace App\Http\Controllers;

use App\Models\CampaignSolaSystem;
use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUsersChanged;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemStatus;
use App\Models\CampaignUser;
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


    public function removeCharForNode(Request $request, $id, $campid)
    {
        $node = NodeJoin::where('campaign_system_id', $id)->get();;
        if ($node->count() > 0) {
            $node = $node->first();
            $user_id = $node->campaign_user_id;
            $campaign_system_status_id = $node->campaign_system_status_id;
            $CampaignSystem = CampaignSystem::where('id', $id)->first();
            $count = $CampaignSystem->node_join_id;
            if ($count == 1) {
                $count = 0;
            } else {
                $count = $count - 1;
            }
            CampaignUser::where('id', $CampaignSystem->campaign_user_id)->update(['campaign_system_id' => null, 'status_id' => 3]);
            $CampaignSystem->update(['campaign_user_id' => $user_id, 'campaign_system_status_id' => $campaign_system_status_id, 'node_join' => $count]);
            $node->delete();
        } else {

            $user_id = CampaignSystem::where('id', $id)->value('campaign_user_id');
            CampaignSystem::where('id', $id)->update($request->all());
            CampaignSystem::where('id', $id)->update(['campaign_system_status_id' => $request['campaign_system_status_id'], 'base_time' => null, 'input_time' => null, 'end_time' => null]);
            CampaignUser::where('id', $user_id)->update(['campaign_system_id' => null, 'status_id' => 3]);
        };





        $flag = collect([
            'flag' => 3,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function removeCharForNodeAdmin(Request $request, $id, $campid)
    {
        $campaignSystemID = $request['campaignSystemID'];
        $campaignUserID = $request['campaignUserID'];

        $campaignSystem = CampaignSystem::where('id', $id)->first();
        if ($campaignSystem->value('campaign_user_id') == null) {
            $campaignSystem->update(['campaign_user_id' => $campaignUserID]);
        } else {
            NodeJoin::create(['campaign_id' => $campid, 'campaign_system_id' => $id, 'campaign_user_id' => $campaignUserID, 'campaign_system_status_id' => 1]);
        }
        CampaignUser::where('id', $campaignUserID)->update(['campaign_system_id' => $campaignSystemID, 'status_id' => 4]);

        $flag = collect([
            'flag' => 3,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function tableindex($campid)
    {
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
                    'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->system_id)->value('id')
                ];
                array_push($nodeJoin, $pull);
            };
        }
        return ["nodeJoin" => $nodeJoin];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        NodeJoin::create($request->all());
        $camp = CampaignSystem::where('id', $request['campaign_system_id'])->first();
        $count = $camp->node_join_id + 1;
        $camp->update(['node_join_id' => $count]);
        $flag = collect([
            'flag' => 3,
            'id' => $campid
        ]);
        broadcast(new CampaignUsersChanged($flag))->toOthers();
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
