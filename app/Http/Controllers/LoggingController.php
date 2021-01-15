<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Models\Logging;
use Illuminate\Http\Request;
use App\Events\CampaignSystemUpdate;
use App\Models\Campaign;
use App\Models\CampaignSolaSystem;
use App\Models\CustomCampaign;
use App\Models\System;
use App\Models\User;
use utils\Helper\Helper;

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
        Helper::logUpdate($campid);

        $campaignname = Helper::campaignName($log->campaign_id);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_systems_id)->first()->systems;
        dd($sola_name);
        $text = $log->user->name . " added node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $campaignname['system_name'], 'text' => $text]);
        $log->save();
    }

    public function nodeDelete(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        Helper::logUpdate($campid);
        $campaignname = Helper::campaignName($campid);
        $text = $log->user->name . " removed node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $campaignname['system_name'], 'text' => $text]);
        $log->save();
    }

    public function nodeAddMulti(Request $request, $campid)
    {
        dd($request);
        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'campaign_sola_systems_id' => $request->campaign_sola_systems_id,
            'user_id' => $request->user_id,
            'campaign_systems_id' => $request->campaign_systems_id,
            'logging_type_id' => 1
        ]);
        Helper::logUpdate($campid);

        $campaignname = Helper::campaignName($log->campaign_id);
        $text = $log->user->name . " added node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $campaignname['system_name'], 'text' => $text]);
        $log->save();
    }

    public function nodeDeleteMulti(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        Helper::logUpdate($campid);
        $campaignname = Helper::campaignName($campid);
        $text = $log->user->name . " removed node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $campaignname['system_name'], 'text' => $text]);
        $log->save();
    }

    public function joinleaveCampaign($campid, $charid, $logtype)
    {

        $log = Logging::create(['campaign_id' => $campid, 'user_id' => $charid, 'logging_type_id' => $logtype]);
        $log->save();
        $campaignname = Helper::campaignName($campid);
        $name = User::where('id', $charid)->value('name');
        if ($logtype == 4) {
            $type = "joined";
        } else {
            $type = "left";
        }
        $text = $name . " " . $type . " the " . $campaignname['campaign_name'] . " campaign at" . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid);
    }

    public function mjoinleaveCampaign($campid, $charid, $logtype)
    {

        $log = Logging::create(['campaign_id' => $campid, 'user_id' => $charid, 'logging_type_id' => $logtype]);
        $log->save();
        $name = User::where('id', $charid)->value('name');
        if ($logtype == 4) {
            $type = "joined";
        } else {
            $type = "left";
        }
        $campaignname = CustomCampaign::where('id', $campid)->value('name');
        $text = $name . " " . $type . " the " . $campaignname . " multi-campaign at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid);
    }

    public function lastchecked(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->save();
        $campaignname = Helper::campaignName($campid);
        $name = User::where('id', $request->user_id)->value('name');
        $text = $name . " updated last checked in " . $campaignname['system_name'] . " for the " . $campaignname['campaign_name'] . " campaign at" . $log->created_at;
        $log->update(['campaign_id' => $campid, 'campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $campaignname['system_name'], 'logging_type_id' => 8, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid);
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
