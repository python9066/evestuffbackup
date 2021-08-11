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
use App\Models\Station;
use App\Models\System;
use App\Models\User;
use PHPUnit\TextUI\Help;
use Spatie\Permission\Models\Role;
use utils\Helper\Helper;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;


class LoggingController extends Controller
{

    use HasPermissions;
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
            $timne = Helper::fixtime($log['created_at']);
            // dd($log);
            $data1 = null;
            $data1 = [
                'id' => $log['id'],
                'campaign_id' => $log['campaign_id'],
                'campaign_name' => $log['campaign_name'],
                'campaign_sola_system_id' => $log['campaign_sola_system_id'],
                'sola_system_name' => $log['sola_system_name'],
                'campaign_system_id' => $log['campaign_system_id'],
                'user_id' => $log['user_id'],
                'user_name' => $log->user()->value('name'),
                'logging_type_id' => $log['logging_type_id'],
                'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
                'text' => $log['text'],
                'created_at' => $timne
            ];
            array_push($data, $data1);
        }

        return ["logs" => $data];
    }

    public function logCampaign($campid)
    {
        // dd($campid);
        $data = [];
        $logs = Logging::where('campaign_id', $campid)->get();
        foreach ($logs as $log) {
            $timne = Helper::fixtime($log['created_at']);
            $data1 = null;
            $data1 = [
                'id' => $log['id'],
                'campaign_id' => $log['campaign_id'],
                'campaign_name' => $log['campaign_name'],
                'campaign_sola_system_id' => $log['campaign_sola_system_id'],
                'sola_system_name' => $log['sola_system_name'],
                'campaign_system_id' => $log['campaign_system_id'],
                'user_id' => $log['user_id'],
                'user_name' => $log->user()->value('name'),
                'logging_type_id' => $log['logging_type_id'],
                'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
                'text' => $log['text'],
                'created_at' => $timne
            ];
            array_push($data, $data1);
        }

        return ["logs" => $data];
    }


    public function rcSheetLogging()
    {
        // dd($campid);
        $check = Auth::user();
        if ($check->hasPermissionTo('view_admin_logs')) {
            $data = [];
            $logs = Logging::where('logging_type_id', '>', 18)->where('logging_type_id', '<', 25)->get();
            foreach ($logs as $log) {
                $timne = Helper::fixtime($log['created_at']);
                $data1 = null;
                $data1 = [
                    'id' => $log['id'],
                    'user_id' => $log['user_id'],
                    'user_name' => $log->user()->value('name'),
                    'logging_type_id' => $log['logging_type_id'],
                    'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
                    'station_id' => $log['station_id'],
                    'station_name' => Station::where('id', $log['station_id'])->value('name'),
                    'text' => $log['text'],
                    'created_at' => $timne
                ];
                array_push($data, $data1);
            }

            return ["logs" => $data];
        } else {
            return ["logs" => null];
        }
    }

    public function logAdmin()
    {
        // dd($campid);
        $data = [];
        $logs = Logging::where('role_id', '!=', null)->get();
        foreach ($logs as $log) {
            $timne = Helper::fixtime($log['created_at']);
            $data1 = null;
            $data1 = [
                'id' => $log['id'],
                'user_id' => $log['user_id'],
                'user_name' => $log->user()->value('name'),
                'logging_type_id' => $log['logging_type_id'],
                'logging_type_name' => LoggingType::where('id', $log['logging_type_id'])->value('name'),
                'role_id' => $log['role_id'],
                'admin_role_name' => $log->role()->value('name'),
                'admin_user_id' => $log['admin_user_id'],
                'admin_user_name' => $log->adminUser()->value('name'),
                'text' => $log['text'],
                'created_at' => $timne
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
            'campaign_system_id' => $request->campaign_system_id,
            'logging_type_id' => 1
        ]);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;


        if ($request->type == 1) {
            $campaignname = Helper::campaignName($log->campaign_id);
            $text = $log->user->name . " added node " . $request->campaign_system_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        } else {
            $campaignname = CustomCampaign::where('id', $request->campaign_id)->value('name');
            $text = $log->user->name . " added node " . $request->node_id . " for the " . $campaignname . " multi campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname, 'sola_system_name' => $sola_name, 'text' => $text]);
        }

        $log->save();
        Helper::logUpdate($campid, $log->id);
    }

    public function nodeDelete(Request $request, $campid)
    {
        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'campaign_sola_system_id' => $request->campaign_sola_system_id,
            'user_id' => $request->user_id,
        ]);
        $log->update(['logging_type_id' => 2]);
        $log->save();
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        if ($request->type == 1) {
            $campaignname = Helper::campaignName($campid);
            $text = $log->user->name . " removed node " . $request->campaign_system_id . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        } else {
            $campaignname = CustomCampaign::where('id', $request->campaign_id)->value('name');
            $text = $log->user->name . " removed node " . $request->campaign_system_id . " for the " . $campaignname . " multi campaign at " . $log->created_at;
            $log->update(['campaign_name' => $campaignname, 'sola_system_name' => $sola_name, 'text' => $text]);
        }

        $log->save();
        Helper::logUpdate($campid, $log->id);
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
        $text = $name . " " . $type . " the " . $campaignname['campaign_name'] . " campaign at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid, $log->id);
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

        Helper::logUpdate($campid, $log->id);
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

        Helper::logUpdate($campid, $log->id);
    }


    public function addremovechar(Request $request, $campid)
    {
        if ($request->type == "added") {
            $logging_type_id = 6;
        } else {
            $logging_type_id = 7;
        }
        $name = User::where('id', $request->user_id)->value('name');
        $log = Logging::create(['campaign_id' => $campid, 'user_id' => $request->user_id, 'logging_type_id' => $logging_type_id]);
        $log->save();


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

        Helper::logUpdate($campid, $log->id);
    }

    public function addRemoveRoles(Request $request)
    {
        if ($request->type == 15) {
            $logging_type_name = "added";
            $text1 = "role to";
        } else {
            $logging_type_name = "removed";
            $text1 = "role from";
        }
        $name = User::where('id', $request->user_id)->value('name');
        $admin_name = User::where('id', $request->userId)->value('name');
        $role_name = Role::where('id', $request->roleId)->value('name');
        $log = Logging::create(['user_id' => $request->user_id, 'logging_type_id' => $request->type, 'role_id' => $request->roleId, 'admin_user_id' => $request->userId]);
        $text = $name . " " . $logging_type_name . " " . $role_name . " " . $text1 . " " . $admin_name . " at " . $log->created_at;
        $log->update(['text' => $text]);
        $log->save();
    }



    public function nodeDeleteMulti(Request $request, $campid)
    {
        $log = Logging::create($request->all());
        $log->update(['logging_type_id' => 2]);
        $log->save();
        $campaignname = Helper::campaignName($campid);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $text = $log->user->name . " removed node " . $request->campaign_system_id . " in " . $sola_name . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid, $log->id);
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
        Helper::logUpdate($campid, $log->id);
    }

    public function nodeAddMulti(Request $request, $campid)
    {

        $log = Logging::create([
            'campaign_id' => $request->campaign_id,
            'campaign_sola_system_id' => $request->campaign_sola_system_id,
            'user_id' => $request->user_id,
            'campaign_system_id' => $request->campaign_system_id,
            'logging_type_id' => 1
        ]);
        $campaignname = Helper::campaignName($log->campaign_id);
        $sola_name = CampaignSolaSystem::where('id', $request->campaign_sola_system_id)->first()->system->system_name;
        $text = $log->user->name . " added node " . $request->campaign_system_id . " in " . $sola_name . " for the " . $campaignname['campaign_name'] . " at " . $log->created_at;
        $log->update(['campaign_name' => $campaignname['campaign_name'], 'sola_system_name' => $sola_name, 'text' => $text]);
        $log->save();
        Helper::logUpdate($campid, $log->id);
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
