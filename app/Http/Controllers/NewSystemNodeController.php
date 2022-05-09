<?php

namespace App\Http\Controllers;

use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $check = NewUserNode::where('node_id', $request->node_id)
            ->count();
        $prime = 1;
        if ($check > 0) {
            $prime = 0;
        }

        $check = NewSystemNode::where('id', $request->node_id)->first();
        if ($check->node_status == 8) {
            $prime = 0;
        }

        if ($prime == 1) {
            $check->update(['node_status' => 2]);
        }

        $new =  NewUserNode::create([
            'primery' => $prime,
            'node_id' => $request->node_id,
            'operation_user_id' => $request->op_user_id,
            'node_status_id' => 2

        ]);

        OperationUser::where('id', $request->op_user_id)
            ->update([
                'new_user_node_id' => $new->id,
                'user_status_id' => 4,
                'system_id' => $request->system_id
            ]);



        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
        Broadcasthelper::broadcastuserSolo($request->opID, $request->op_user_id, 6);
        Broadcasthelper::broadcastuserOwnSolo($request->op_user_id, Auth::id(), 3);
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
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }


                break;
            case 2: // * warm up
                break;
            case 3: // * Hacking
                break;
            case 4: // * Success
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
            case 5: // * Faild
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
            case 6: // * Pushed off
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
            case 7: // * Hostile Hacking
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
            case 8: // * Friendly Hacking
                $userNode = NewUserNode::where('node_id', $id)
                    ->where(
                        'primery',
                        1
                    )->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
            case 9: // * Passive
                $userNode = NewUserNode::where('node_id', $id)
                    ->where('primery', 1)->first();
                if ($userNode) {
                    $opUser = OperationUser::where('id', $userNode->operation_user_id)->first();
                    $opUserID = $opUser->id;
                    $user = User::where('id', $opUser->user_id)->first();
                    $userID = $user->id;
                    $opUser->update([
                        'new_user_node_id' => null,
                        'user_status_id' => 3
                    ]);


                    $userNode->delete();

                    $oldCheck = NewUserNode::where('node_id', $id)->oldest()->first();
                    if ($oldCheck) {
                        $oldCheck->update('primery', 1);
                    }

                    Broadcasthelper::broadcastuserOwnSolo($opUserID, $userID, 3);
                    Broadcasthelper::broadcastuserSolo($request->opID, $opUserID, 6);
                }
                break;
        }
        NewSystemNode::where('id', $id)->update(['node_status' => $request->status_id]);
        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $systemNode = NewSystemNode::where('id', $id)->first();
        $system_id = $systemNode->system_id;
        $nodes = NewUserNode::where('node_id', $id)->get();
        foreach ($nodes as $node) {
            $OpUser = OperationUser::where('new_user_node_id', $node->operation_user_id)->first();
            $OpUser->update([
                'new_user_node_id' => null,
                'user_status_id' => 3
            ]);

            Broadcasthelper::broadcastuserOwnSolo($OpUser->id, $OpUser->user_id, 3);
            Broadcasthelper::broadcastuserSolo($OpUser->operation_id, $OpUser->id, 6);
            NewUserNode::where('node_id', $node->id)->delete();
        }
        // TODO Change this so it only gets Campaigns what are active.
        $systemNode->delete();
        Broadcasthelper::broadcastsystemSolo($system_id, 7);
    }
}
