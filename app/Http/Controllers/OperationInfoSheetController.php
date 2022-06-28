<?php

namespace App\Http\Controllers;

use App\Models\OperationInfo;
use Illuminate\Http\Request;

class OperationInfoSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return ['data' => operationInfoSoloPagePull($id)];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $operation = OperationInfo::where('id', $id)->first();
        $operation->form_op_allies_ready = intval($request->form_op_allies_ready);
        $operation->form_op_blackhand_ready = intval($request->form_op_blackhand_ready);
        $operation->form_op_capital_fc_ready = intval($request->form_op_capital_fc_ready);
        $operation->form_op_fc_ready = intval($request->form_op_fc_ready);
        $operation->form_op_gsfoe_ready = intval($request->form_op_gsfoe_ready);
        $operation->form_op_gsol_ready = intval($request->form_op_gsol_ready);
        $operation->form_op_recon_ready = intval($request->form_op_recon_ready);
        $operation->form_op_scouts_ready = intval($request->form_op_scouts_ready);
        $operation->planing_op_allies_infored = intval($request->planing_op_allies_infored);
        $operation->planing_op_capital_fc_found = intval($request->planing_op_capital_fc_found);
        $operation->planing_op_doctromes_decoded = intval($request->planing_op_doctromes_decoded);
        $operation->planing_op_fc_found = intval($request->planing_op_fc_found);
        $operation->planing_op_posted = intval($request->planing_op_posted);
        $operation->planing_op_pre_ping = intval($request->planing_op_pre_ping);
        $operation->planing_op_recon_alerted = intval($request->planing_op_recon_alerted);
        $operation->post_op_coord_done = intval($request->post_op_coord_done);
        $operation->post_op_defrief_done = intval($request->post_op_defrief_done);
        $operation->post_op_fc_done = intval($request->post_op_fc_done);
        $operation->post_op_recon_done = intval($request->post_op_recon_done);
        $operation->post_op_scouts_done = intval($request->post_op_scouts_done);
        $operation->save();
        operationInfoSoloPageBroadcast($id, 1);
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
