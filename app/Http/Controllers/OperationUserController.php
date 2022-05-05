<?php

namespace App\Http\Controllers;

use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewNodeCampaignUser;
use App\Models\OperationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Broadcasthelper\Broadcasthelper;
use utils\Campaignhelper\Campaignhelper;
use utils\NewCampaignhelper\NewCampaignhelper;

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

        if (Auth::id() == $userid) {
            Broadcasthelper::broadcastuserOwnSolo($new->id, $userid, 3);
        }

        Broadcasthelper::broadcastuserSolo($opID, $new->id, 6);
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
        //TODO remove from other operations and nodes in there to a new operations


        OperationUser::where('id', $id)->update($request->all());

        if (Auth::id() == $userid) {
            Broadcasthelper::broadcastuserOwnSolo($id, $userid, 3);
        }
        if (Auth::id() == $userid) {
            Broadcasthelper::broadcastuserSolo($opID, $id, 6);
        }
        // TODO Add baordcsat to update stuff
    }

    public function updateremove(Request $request, $id, $opID, $userid)
    {

        //TODO remove from other operations and nodes in there to a new operations

        OperationUser::where('id', $id)->update($request->all());
        // TODO Add boradcast to update info
        if (Auth::id() == $userid) {
            Broadcasthelper::broadcastuserOwnSolo($id, $userid, 3);
        }


        $flag = collect([
            'flag' => 5,
            'id' => $opID,
            "userid" => $id
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
            Broadcasthelper::broadcastuserOwnSolo($id, $userid, 5);
        }

        // TODO Sort out boardcasting
    }
}
