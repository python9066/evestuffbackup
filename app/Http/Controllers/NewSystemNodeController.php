<?php

namespace App\Http\Controllers;

use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use Illuminate\Http\Request;

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
        $campaignIDs = NewCampaignSystem::where('system_id', $request->system_id)->select('campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->select('operation_id');
        return $obIDS;
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
