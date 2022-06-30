<?php

namespace App\Http\Controllers;

use App\Models\OperationInfoFleet;
use Illuminate\Http\Request;

class OperationInfoFleetController extends Controller
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
    public function store($id)
    {
        $count = OperationInfoFleet::where('operation_info_id', $id)->count();
        $new = new OperationInfoFleet();
        $new->name = "Fleet - " . $count + 1;
        $new->operation_info_id = $id;
        $new->save();
        operationInfoSoloPageBroadcast($id, 1);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function name(Request $request, $id)
    {
        $fleet = OperationInfoFleet::where('id', $id)->first();
        if ($request->name != null) {
            $userID = checkUserName($request->name, $request->opID);
            if ($request->type == 1) {
                $fleet->fc_id = $userID;
            } elseif ($request->type == 2) {
                $fleet->boss_id = $userID;
            }
        } else {
            if ($request->type == 1) {
                $fleet->fc_id = null;
            } elseif ($request->type == 2) {
                $fleet->boss_id = null;
            }
        }
        $fleet->save();
        operationInfoSoloPageFleetBroadcast($fleet->id, 2);
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

        $fleet = OperationInfoFleet::where('id', $id)->first();
        $fleet->name = $request->name;
        $fleet->gsf_fleet = $request->gsf_fleet;
        $fleet->mumble_id = $request->mumble_id;
        $fleet->doctrine_id = $request->doctrine_id;
        $fleet->alliance_id = $request->alliance_id;
        $fleet->save();

        operationInfoSoloPageFleetBroadcast($fleet->id, 2);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleet = OperationInfoFleet::where('id', $id)->first();
        $opID = $fleet->operation_info_id;
        $fleet->delete();
        operationInfoSoloPageBroadcast($opID, 1);
    }
}
