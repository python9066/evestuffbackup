<?php

namespace App\Http\Controllers;

use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\OperationUser;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;
use utils\Campaignhelper\Campaignhelper;

class NewSystemNodeController extends Controller
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
        NewSystemNode::create($request->all());
        // TODO Change this so it only gets Campaigns what are active.
        $campaignIDs = NewCampaignSystem::where('system_id', $request->system_id)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = Campaignhelper::systemSolo($request->system_id);




        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => 7,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }
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


    public function addCharToNode(Request $request)
    {
        $check = OperationUser::where('new_system_node_id', $request->node_id)
            ->count();
        $prime = 1;
        if ($check > 0) {
            $prime = 0;
        }

        $check = NewSystemNode::where('id', $request->node_id)->first();
        if ($check->node_status == 8) {
            $prime = 0;
        }

        OperationUser::where('id', $request->op_user_id)
            ->update([
                'primery' => $prime,
                'new_system_node_id'
            ]);

        $campaignIDs = NewCampaignSystem::where('system_id', $request->system_id)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = Campaignhelper::systemSolo($request->system_id);




        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => 7,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }

        $message = Campaignhelper::opUserSolo($request->opID, $request->op_user_id);
        $flag = collect([
            // * 6 is to add newley made char to op list
            'flag' => 6,
            'message' => $message,
            'id' => $request->opID
        ]);

        broadcast(new OperationUpdate($flag));
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
        $system_id = NewSystemNode::where('id', $id)->pluck('system_id');
        NewSystemNode::where('id', $id)->delete();
        // TODO Change this so it only gets Campaigns what are active.
        $campaignIDs = NewCampaignSystem::where('system_id', $system_id)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = Campaignhelper::systemSolo($system_id);
        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => 7,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }
}
