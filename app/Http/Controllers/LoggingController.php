<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Models\Logging;
use Illuminate\Http\Request;
use App\Events\CampaignSystemUpdate;
use App\Models\Campaign;
use App\Models\CampaignSolaSystem;
use App\Models\CustomCampaign;
use App\Models\LoggingType;
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
        $data = [];
        $logs = Logging::all();
        foreach ($logs as $log) {
            $data1 = null;
            $data1 = [
                'id' => $log['id'],
                'campaign_id' => $log['campaign_id'],
                'campaign_name' => $log['campaign_name'],
                'campaign_sola_system_id' => $log['campaign_sola_system_id'],
                'sola_system_name' => $log['sola_system_name'],
                'campaign_system_id' => $log['campaign_system_id'],
                'user_id' => $log['user_id'],
                'user_name' => User::where('id', $log['user_id'])->value('user_name'),
                'user_name_test' => $log->user->user_name,
                'logging_type_id' => $log['logging_type_id'],
                'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
                'text' => $log['text'],
                'created_at' => $log['created_at']
            ];
            array_push($data, $data1);
        }

        return ["logs" => $data];
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
            'campaign_sola_system_id' => $request->campaign_sola_system_id,
            'user_id' => $request->user_id,
            'campaign_systems_id' => $request->campaign_systems_id,
            'logging_type_id' => 1
        ]);
        $campaignname = Helper::campaignName($log->campaign_id);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $text = $log->user->name . " added node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid);
    }

    public function nodeDelete(Request $request, $campid)
    {
        dd($request);
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $campaignname = Helper::campaignName($campid);
        $text = $log->user->name . " removed node " . $request->campaign_systems_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid);
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

    public function lastchecked(Request $request, $campid)
    {

        $log = Logging::create($request->all());
        $log->save();

        $name = User::where('id', $request->user_id)->value('name');
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;

        if (Campaign::where('id', $campid)->count() == 0) {
            $campaignname = CustomCampaign::where('id', $campid)->value('name');
            $text = $name . " updated last checked in " . $sola_name . " for the " . $campaignname . " multi campaign at " . $log->created_at;
            $log->update(['campaign_id' => $campid, 'campaign_name' => $campaignname, 'sola_system_name' => $sola_name, 'logging_type_id' => 8, 'text' => $text]);
            $log->save();
        } else {
            $campaignname = Helper::campaignName($campid);
            $text = $name . " updated last checked in " . $sola_name . " for the " . $campaignname['campaign_name'] . " campaign at " . $log->created_at;
            $log->update(['campaign_id' => $campid, 'campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'logging_type_id' => 8, 'text' => $text]);
            $log->save();
        }

        Helper::logUpdate($campid);
    }

    public function systemscout(Request $request, $campid)
    {
        if ($request->type == "added") {
            $logging_type_id = 9;
        } else {
            $logging_type_id = 10;
        }
        $log = Logging::create(['campaign_id' => $campid, 'user_id' => $request->user_id, 'campaign_sola_system_id' => $request->campaign_sola_system_id, 'logging_type_id' => $logging_type_id]);
        $log->save();

        $name = User::where('id', $request->user_id)->value('name');
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;

        if (Campaign::where('id', $campid)->count() == 0) {
            $campaignname = CustomCampaign::where('id', $campid)->value('name');
            $text = $name . " " . $request->type . " as system scout of " . $sola_name . " for the " . $campaignname . " multi campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname, 'sola_system_name' => $sola_name, 'text' => $text]);
            $log->save();
        } else {
            $campaignname = Helper::campaignName($campid);
            $text = $name . " " . $request->type . " as system scout of " . $sola_name . " for the " . $campaignname['campaign_name'] . " campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
            $log->save();
        }

        Helper::logUpdate($campid);
    }


    public function addremovechar(Request $request, $campid)
    {
        if ($request->type == "added") {
            $logging_type_id = 6;
        } else {
            $logging_type_id = 7;
        }
        $log = Logging::create(['campaign_id' => $campid, 'user_id' => $request->user_id, 'logging_type_id' => $logging_type_id]);
        $log->save();

        $name = User::where('id', $request->user_id)->value('name');

        if (Campaign::where('id', $campid)->count() == 0) {
            $campaignname = CustomCampaign::where('id', $campid)->value('name');
            $text = $name . " " . $request->type . " a characters to the " . $campaignname . " multi campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname, 'text' => $text]);
            $log->save();
        } else {
            $campaignname = Helper::campaignName($campid);
            $text = $name . " " . $request->type . " a characters to the " . $campaignname['campaign_name'] . " campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname['campaign_name'], 'text' => $text]);
            $log->save();
        }

        Helper::logUpdate($campid);
    }


    public function nodeDeleteMulti(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        $campaignname = Helper::campaignName($campid);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $text = $log->user->name . " removed node " . $request->campaign_systems_id . " in " . $sola_name . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
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

    public function nodeAddMulti(Request $request, $campid)
    {

        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'campaign_sola_system_id' => $request->campaign_sola_system_id,
            'user_id' => $request->user_id,
            'campaign_systems_id' => $request->campaign_systems_id,
            'logging_type_id' => 1
        ]);
        $campaignname = Helper::campaignName($log->campaign_id);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $text = $log->user->name . " added node " . $request->campaign_systems_id . " in " . $sola_name . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
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
