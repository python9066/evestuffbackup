<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Models\Logging;
use Illuminate\Http\Request;
use App\Events\CampaignSystemUpdate;

class LoggingController extends Controller
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
    public function nodeAdd(Request $request, $campid)
    {
        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'campaign_sola_systems_id' => $request->campaign_sola_systems_id,
            'user_id' => $request->user_id,
            'campaign_systems_id' => $request->campaign_systems_id,
            'logging_type_id' => 1
        ]);
        $flag = collect([
            'flag' => 10,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function nodeDelete(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        $flag = collect([
            'flag' => 10,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function store(Request $request, $campid)
    {
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
