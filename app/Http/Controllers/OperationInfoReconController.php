<?php

namespace App\Http\Controllers;

use App\Models\OperationInfoRecon;
use Illuminate\Http\Request;

class OperationInfoReconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = operationReconAll();
        return ['recon' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $done =  checkUserNameRecon($request->name, $request->opID);

        if (!$done) {
            return response()->json([
                'status' => true,
                'message' => 'user not found',
                'errors' => 'all'
            ], 201);
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

    public function removeFromOp(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $request->id)->first();
        $recon->operation_info_id = 0;
        $recon->operation_info_recon_status_id = 0;
        if ($recon->operation_system_id) {
            $recon->operation_system_id = null;
        } else {
            $recon->operation_system_id = null;
        }

        if ($recon->operation_info_fleet_id) {
            $recon->operation_info_fleet_id = null;
        } else {
            $recon->operation_info_fleet_id = null;
        }


        $recon->save();
        operationReconRemoveSoloBcast($recon->id, $id, 13);
    }

    public function updateDeadStatus(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $id)->first();
        $recon->dead = $request->dead;
        $recon->save();

        operationReconSoloBcast($id, 5);
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
