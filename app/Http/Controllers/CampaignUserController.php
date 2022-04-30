<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserDelete;
use App\Events\CampaignUserNew;
use App\Events\CampaignUserUpdate;
use App\Events\OperationUpdate;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\NewNodeCampaignUser;
use App\Models\OperationUser;
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

        $new = CampaignUser::create($request->all());
        $userid = $new->id;
        $user = CampaignUserRecords::where('id', $userid)->first();
        $flag = null;
        $flag = collect([
            'message' => $user,
            'id' => $campid
        ]);
        broadcast(new CampaignUserNew($flag))->toOthers();
    }

    public function newstore(Request $request, $opID)
    {

        $new = OperationUser::create($request->all());
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
        $message = CampaignUserRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid
        ]);
        broadcast(new CampaignUserUpdate($flag));
    }

    public function updateadd(Request $request, $id, $campid)
    {
        $node = CampaignSystem::where('campaign_user_id', $id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }


        CampaignUser::find($id)->update($request->all());
        $message = CampaignUserRecords::where('id', $id)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid
        ]);
        broadcast(new CampaignUserNew($flag))->toOthers();
    }

    public function newupdateadd(Request $request, $id, $opID)
    {
        $node = NewNodeCampaignUser::where('operation_user_id', $id)->first();

        if ($node != null) {
            $node->update(['operation_user_id' =>  null, 'status_id' => 1, 'end_time' => null]);
            $node->save();
            // TODO Add baordcsat to update stuff
        }


        OperationUser::where('id', $id)->update($request->all());
        // TODO Add baordcsat to update stuff
    }

    public function updateremove(Request $request, $id, $campid)
    {

        $node = CampaignSystem::where('campaign_user_id', $id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
            $flag = null;
            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
        }

        CampaignUser::find($id)->update($request->all());
        $user_id = CampaignUserRecords::where('id', $id)->value('id');
        $flag = collect([
            'userid' => $user_id,
            'id' => $campid
        ]);
        broadcast(new CampaignUserDelete($flag))->toOthers();
    }

    public function newupdateremove(Request $request, $id)
    {

        $node = NewNodeCampaignUser::where('operation_user_id', $id)->first();

        if ($node != null) {
            $node->update(['operation_user_id' =>  null, 'status_id' => 1, 'end_time' => null]);
            $node->save();
            // TODO Add boradcast to update node
        }

        OperationUser::where('id', $id)->update($request->all());
        // TODO Add boradcast to update info
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
        $flag = collect([
            'userid' => $id,
            'id' => $campid
        ]);

        broadcast(new CampaignUserDelete($flag))->toOthers();
    }

    public function newdestroy($id, $opID)
    {

        OperationUser::destroy($id);

        $flag = collect([
            'userid' => $id,
            'id' => $opID
        ]);

        broadcast(new OperationUpdate($flag));

        // TODO Sort out boardcasting
    }
}
