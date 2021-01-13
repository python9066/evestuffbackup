<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Models\Logging;
use Illuminate\Http\Request;

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
    public function add(Request $request, $campid)
    {
        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'sola_id' => $request->sola_id,
            'user_id' => $request->user_id,
            'logging_type_id' => 1
        ]);

        $node_id = CampaignSystem::where('campaign_id', $request->campaign_id)->where('node_id', $request->node_id)->value('id');
        dd($node_id, $log);
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
