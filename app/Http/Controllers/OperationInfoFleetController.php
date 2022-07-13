<?php

namespace App\Http\Controllers;

use App\Models\OperationInfoFleet;
use App\Models\OperationInfoRecon;
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
        operationInfoSoloPageFleetBroadcast($new->id, $id, 2);
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
        operationInfoSoloPageFleetBroadcast($fleet->id, $fleet->operation_info_id, 2);
    }

    public function reconAdd(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $request->reconID)->first();
        $recon->operation_info_fleet_id = $id;
        if ($recon->operation_info_recon_status_id == 3) {
            $recon->operation_info_recon_status_id = 4;
        } else {
            $recon->operation_info_recon_status_id = 2;
        }
        $recon->role_id = $request->role;
        $recon->save();

        operationReconSoloBcast($recon->id, 5);
        operationInfoSoloPageFleetBroadcast($id, $request->opID, 2);
    }

    public function reconRemove(Request $request, $id)
    {


        $recon = OperationInfoRecon::where('id', $request->id)->first();
        $fleetID = $recon->operation_info_fleet_id;
        $recon->operation_info_fleet_id = null;
        if ($recon->operation_info_recon_status_id == 4) {
            $recon->operation_info_recon_status_id = 3;
        } else {
            $recon->operation_info_recon_status_id = null;
        }
        $recon->role_id = null;
        $recon->save();

        operationReconSoloBcast($recon->id, 5);
        operationInfoSoloPageFleetBroadcast($fleetID, $id, 2);
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

        operationInfoSoloPageFleetBroadcast($fleet->id, $fleet->operation_info_id, 2);
    }

    /**
     * Remove the specified resource fromddddd storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleet = OperationInfoFleet::where('id', $id)->first();
        $opID = $fleet->operation_info_id;
        $recon = OperationInfoRecon::where('id', $fleet->recon_id)->first();
        if ($recon) {
            $recon->operation_info_fleet_id = null;
            $recon->operation_info_recon_status_id = 1;
            $recon->save();
            operationReconSoloBcast($recon->id, 5);
        }
        $fleet->delete();
        operationInfoSoloPageFleetBroadcastDelete($id, $opID, 6);
    }
}
