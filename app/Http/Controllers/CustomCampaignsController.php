<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemDelete;
use App\Models\CampaignJoin;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\CustomCampaign;
use Illuminate\Http\Request;
use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserUpdate;
use App\Events\NodeJoinDelete;
use App\Models\CampaignUserRecords;
use App\Models\NodeJoin;

class CustomCampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $pull = CustomCampaign::where('status_id', "<", 3)->with("status")->get();
        foreach ($pull as $pull) {
            $data = [];
            $data = [
                'id' => $pull['id'],
                'name' => $pull['name'],
                'status_id' => $pull['status_id'],
                'status_name' => $pull['status']['name']
            ];
            array_push($list, $data);
        };

        return ['campaigns' => $list];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid, $name)
    {
        $data = $request->all();
        // dd($data);
        CustomCampaign::create(['id' => $campid, 'name' => $name]);
        foreach ($data as $data) {
            // dd($data);
            CampaignJoin::create(['custom_campaign_id' => $campid, 'campaign_id' => $data]);
            $solas = CampaignSolaSystem::where('campaign_id', $data)->get();
            foreach ($solas as $sola) {
                if (CampaignSolaSystem::where('campaign_id', $campid)->where('system_id', $sola['system_id'])->count() < 1) {
                    CampaignSolaSystem::create(['system_id' => $sola['system_id'], 'campaign_id' => $campid]);
                };
            }
        }
    }

    public function edit(Request $request, $campid, $name)
    {

        CampaignJoin::where('custom_campaign_id', $campid)->delete();
        CampaignSolaSystem::where('campaign_id', $campid)->delete();
        $data = $request->all();
        // dd($data);
        CustomCampaign::find($campid)->update(['name' => $name]);
        foreach ($data as $data) {
            // dd($data);
            CampaignJoin::create(['custom_campaign_id' => $campid, 'campaign_id' => $data]);
            $solas = CampaignSolaSystem::where('campaign_id', $data)->get();
            foreach ($solas as $sola) {
                if (CampaignSolaSystem::where('campaign_id', $campid)->where('system_id', $sola['system_id'])->count() < 1) {
                    CampaignSolaSystem::create(['system_id' => $sola['system_id'], 'campaign_id' => $campid]);
                };
            }
            $systemNodes = CampaignSystem::where('campaign_id', $data)->where('custom_campaign_id')->get();
            if ($systemNodes->count() > 0) {
                foreach ($systemNodes as $systemNode) {

                    $flag = null;
                    $flag = collect([
                        'campSysID' => $systemNode->id,
                        'id' => $campid
                    ]);
                    broadcast(new CampaignSystemDelete($flag));


                    $user =  CampaignUser::where('campaign_system_id', $systemNode->id)->first();
                    if ($user->count() > 0) {
                        $user->update(['campaign_system_id' => null, 'status_id' => null]);
                        $message = CampaignUserRecords::where('id', $user->id)->first();
                        $flag = collect([
                            'message' => $message,
                            'id' => $campid
                        ]);
                        broadcast(new CampaignUserUpdate($flag));

                        if ($systemNode->node_join_count > 0) {
                            $nodes = NodeJoin::where('campaign_system_id', $systemNode->id);
                            foreach ($nodes as $node) {
                                $flag = null;
                                $flag = collect([
                                    'joinNodeID' => $node->id,
                                    'id' => $campid
                                ]);
                                broadcast(new NodeJoinDelete($flag));

                                $user = CampaignUser::where('campaign_system_id', $node->campaign_system_id)->first();
                                $user->update(['campaign_system_id' => null, 'status_id' => null]);
                                $message = CampaignUserRecords::where('id', $user->id)->first();
                                $flag = collect([
                                    'message' => $message,
                                    'id' => $campid
                                ]);
                                broadcast(new CampaignUserUpdate($flag));
                                $node->delete();
                            }
                        }
                    }

                    $systemNode->delete();
                }
            }
        }

        $flag = collect([
            'flag' => 11,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
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

        CustomCampaign::destroy($id);
        CampaignJoin::where('custom_campaign_id', $id)->delete();
        CampaignSystem::where('custom_campaign_id', $id)->delete();
        CampaignSystemUsers::where('custom_campaign_id', $id)->delete();
        CampaignUser::where('campaign_id', $id)->update(["system_id" => null, "status_id" => 1]);
        CampaignSolaSystem::where('campaign_id', $id)->delete();
    }
}
