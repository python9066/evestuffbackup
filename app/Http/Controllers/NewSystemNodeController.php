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
use utils\Broadcasthelper\Broadcasthelper;
use utils\Campaignhelper\Campaignhelper;
use utils\NewCampaignhelper\NewCampaignhelper;

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
        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
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
                'new_system_node_id' => $request->node_id,
                'user_status_id' => 4
            ]);

        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
        Broadcasthelper::broadcastuserSolo($request->opID, $request->op_user_id);
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

    public function updateStatus(Request $request, $id)
    {
        switch ($request->status_id) {
            case 1: // * new
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 2: // * warm up
                break;
            case 3: // * Hacking
                break;
            case 4: // * Success
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 5: // * Faild
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 6: // * Pushed off
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 7: // * Hostile Hacking
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 8: // * Friendly Hacking
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
            case 9: // * Passive
                OperationUser::where('new_system_node_id', $id)->update(['new_system_node_id' => null, 'user_status_id' => 1]);
                break;
        }
        NewSystemNode::where('id', $id)->update(['node_status' => $request->status_id]);
        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
        Broadcasthelper::broadcastuserSolo();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $system_id = NewSystemNode::where('id', $id)
            ->pluck('system_id');

        NewSystemNode::where('id', $id)
            ->delete();

        OperationUser::where('new_system_node_id', $id)
            ->update([
                'new_system_node_id' => null,
                'user_status_id' => 3
            ]);

        // TODO Change this so it only gets Campaigns what are active.
        Broadcasthelper::broadcastsystemSolo($system_id, 7);
    }
}
