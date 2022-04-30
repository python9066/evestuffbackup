<?php

namespace App\Http\Controllers;

use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewNodeCampaignUser;
use App\Models\OperationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Campaignhelper\Campaignhelper;

class OperationUserController extends Controller
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
    public function store(Request $request, $opID, $userid)
    {

        $new = OperationUser::create($request->all());
        $message = Campaignhelper::ownUsersolo($new->id);

        if (Auth::id() == $new->user_id) {

            $flag = collect([
                // * 3 add char to char table
                'flag' => 3,
                'message' => $message,
                'id' => $userid
            ]);

            broadcast(new OperationUpdate($flag));
        }

        $message = Campaignhelper::opUserSolo($opID, $new->id);
        $flag = collect([
            // * 6 is to add newley made char to op list
            'flag' => 6,
            'message' => $message,
            'id' => $opID
        ]);

        broadcast(new OperationUpdate($flag));
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

    public function updateadd(Request $request, $id, $opID, $userid)
    {
        $node = NewNodeCampaignUser::where('operation_user_id', $id)->first();

        if ($node != null) {
            $node->update(['operation_user_id' =>  null, 'status_id' => 1, 'end_time' => null]);
            $node->save();
            // TODO Add baordcsat to update stuff
        }


        OperationUser::where('id', $id)->update($request->all());

        if (Auth::id() == $userid) {
            $message = Campaignhelper::ownUsersolo($id);
            $flag = collect([
                'flag' => 3,
                'userid' => $id,
                'id' => $userid,
                'message' => $message
            ]);

            broadcast(new OperationOwnUpdate($flag));
        }

        if (Auth::id() == $userid) {
            $message = Campaignhelper::opUserSolo($opID, $id);
            $flag = collect([
                'flag' => 6,
                'id' => $opID,
                'message' => $message
            ]);

            broadcast(new OperationUpdate($flag));
        }
        // TODO Add baordcsat to update stuff
    }

    public function updateremove(Request $request, $id)
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
    public function destroy($id, $opID, $userid)
    {

        OperationUser::destroy($id);

        $flag = collect([
            'flag' => 5,
            'userid' => $id,
            'id' => $opID
        ]);

        broadcast(new OperationUpdate($flag));

        if (Auth::id() == $userid) {

            $flag = collect([
                'flag' => 5,
                'userid' => $id,
                'id' => $userid
            ]);

            broadcast(new OperationOwnUpdate($flag));
        }

        // TODO Sort out boardcasting
    }
}
