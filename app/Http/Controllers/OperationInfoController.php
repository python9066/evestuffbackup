<?php

namespace App\Http\Controllers;

use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\OperationInfo;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoMessage;
use App\Models\OperationInfoRecon;
use App\Models\OperationInfoSystem;
use App\Models\OperationInfoSystemRecon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OperationInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = operationInfoAll();

        return ['opinfo' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->can('edit_opertaion_info')) {

            $new = new OperationInfo();
            $new->link = Str::uuid();
            $new->name = $request->name;
            $new->info = $request->info;
            $new->status_id = 1;
            $new->save();
            operationInfoSoloBroadcast($new->id, 2);
            Artisan::call("get:dankdocs");
        }
    }


    public function updateNote(Request $request, $id)
    {
        $systems = OperationInfoSystem::where('system_id', $id)->get();
        foreach ($systems as $system) {
            $system->notes = $request->notes;
            $system->save();
            operationInfoSystemsSoloBcast($system->operation_info_id, $id, 14);
        }
    }

    public function updateJam(Request $request, $id)
    {
        $systems = OperationInfoSystem::where('system_id', $id)->get();
        foreach ($systems as $system) {
            $system->jammed_status = $request->jam;
            $system->save();
            operationInfoSystemsSoloBcast($system->operation_info_id, $id, 14);
        }
    }

    public function updateRecon(Request $request, $id)
    {
        $reconArray = $request->recons;
        if (count($reconArray) > 0) {
            if (gettype($reconArray[0]) == "array") {
                $reconCollect = collect($reconArray);
                $reconCollect = $reconCollect->pluck('id');
                $reconArray = $reconCollect->all();
            }
        }
        $old = OperationInfoSystemRecon::whereNotIn('operation_info_recon_id', $reconArray)
            ->where('operation_info_system_id', $id)->get();
        foreach ($old as $old) {
            $recon = OperationInfoRecon::where('id', $old->operation_info_recon_id)->first();
            $recon->system_id = null;
            if ($recon->operation_info_recon_status_id == 4) {
                $recon->operation_info_recon_status_id = 2;
            } else {
                $recon->operation_info_recon_status_id = 1;
            }
            $recon->save();
            $old->delete();
            operationReconSoloBcast($old->operation_info_recon_id, 5);
        }
        foreach ($reconArray as $reconID) {
            $check = OperationInfoSystemRecon::where('operation_info_system_id', $id)
                ->where('operation_info_recon_id', $reconID)
                ->count();
            if ($check == 0) {
                $new = new OperationInfoSystemRecon();
                $new->operation_info_recon_id = $reconID;
                $new->operation_info_system_id = $id;
                $new->save();
                $recon = OperationInfoRecon::where('id', $reconID)->first();
                $recon->system_id = $id;
                if ($recon->operation_info_recon_status_id == 2) {
                    $recon->operation_info_recon_status_id = 4;
                } else {
                    $recon->operation_info_recon_status_id = 3;
                }
                $recon->save();

                operationReconSoloBcast($reconID, 5);
            }
        };

        operationInfoSystemsSoloBcast($request->opID, $id, 14);
    }


    public function deleteRecon(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $request->reconID)->first();
        $recon->system_id = null;
        if ($recon->operation_info_recon_status_id == 4) {
            $recon->operation_info_recon_status_id = 2;
        } else {
            $recon->operation_info_recon_status_id = 1;
        }
        $recon->save();
        OperationInfoSystemRecon::where('operation_info_recon_id', $request->reconID)
            ->where('operation_info_system_id', $id)->delete();
        operationReconSoloBcast($request->reconID, 5);
        operationInfoSystemsSoloBcast($request->opID, $id, 14);
    }


    public function updateStartTime(Request $request, $id)
    {
        $operation = OperationInfo::where('id', $id)->first();
        $operation->start = $request->start;
        $operation->save();
        operationInfoSoloPageBroadcast($id, 1);
        operationInfoSoloBroadcast($id, 2);
    }




    public function editHackOperation(Request $request, $id)
    {

        if ($request->systemsToUpdate) {

            foreach ($request->systemsToUpdate as $systemID) {
                $check = OperationInfoSystem::where('system_id', $systemID)->where('operation_info_id', $id)->count();
                if ($check == 0) {
                    $new = new OperationInfoSystem();
                    $new->operation_info_id = $id;
                    $new->system_id = $systemID;
                    $new->save();
                }
            }
            $ss = OperationInfoSystem::where('operation_info_id', $id)
                ->whereNull("new_operation_id")
                ->whereNotIn('system_id', $request->systemsToUpdate)
                ->get();
            foreach ($ss as $s) {
                $recons = OperationInfoRecon::where('system_id', $s->system_id)->get();
                foreach ($recons as $recon) {
                    $recon->system_id = null;
                    if ($recon->operation_info_recon_status_id == 4) {
                        $recon->operation_info_recon_status_id = 2;
                    } else {
                        $recon->operation_info_recon_status_id = 1;
                    }
                    $reconSystem = OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)->first();
                    $reconSystem->delete();
                    $recon->save();
                    operationReconSoloBcast($recon->id, 5);
                }
                $s->delete();
            }
        } else {

            $ss = OperationInfoSystem::where('operation_info_id', $id)
                ->whereNull("new_operation_id")
                ->get();
            foreach ($ss as $s) {
                $recons = OperationInfoRecon::where('system_id', $s->system_id)->get();
                foreach ($recons as $recon) {
                    $recon->system_id = null;
                    if ($recon->operation_info_recon_status_id == 4) {
                        $recon->operation_info_recon_status_id = 2;
                    } else {
                        $recon->operation_info_recon_status_id = 1;
                    }
                    $reconSystem = OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)->first();
                    $reconSystem->delete();
                    $recon->save();
                    operationReconSoloBcast($recon->id, 5);
                    operationInfoSystemsSoloBcast($request->opID, $id, 14);
                }
                $s->delete();
            }
        }
        if ($request->operation_id) {
            $operationInfo = OperationInfo::where('id', $id)->first();
            if ($operationInfo->operation_id != $request->operation_id) {
                $oldOperationID = $operationInfo->operation_id;
                $ss = OperationInfoSystem::where('new_operation_id', $oldOperationID)->get();
                foreach ($ss as $s) {
                    $recons = OperationInfoRecon::where('system_id', $s->system_id)->get();
                    foreach ($recons as $recon) {
                        $recon->system_id = null;
                        if ($recon->operation_info_recon_status_id == 4) {
                            $recon->operation_info_recon_status_id = 2;
                        } else {
                            $recon->operation_info_recon_status_id = 1;
                        }
                        $reconSystem = OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)->first();
                        $reconSystem->delete();
                        $recon->save();
                        operationReconSoloBcast($recon->id, 5);
                    }
                    $s->delete();
                }
            }
            $operationInfo->operation_id = $request->operation_id;
            $operationInfo->save();
            $campaignIDs = NewCampaignOperation::where('operation_id', $request->operation_id)->pluck('campaign_id');
            $systemIDs = NewCampaignSystem::whereIn('new_campaign_id', $campaignIDs)->pluck('system_id');
            $systemIDs = $systemIDs->unique();
            $systemIDs = $systemIDs->values();
            foreach ($systemIDs as $systemID) {
                $check = OperationInfoSystem::where('system_id', $systemID)->where('operation_info_id', $id)->count();
                if ($check == 0) {
                    $new = new OperationInfoSystem();
                    $new->operation_info_id = $id;
                    $new->system_id = $systemID;
                    $new->new_operation_id = $request->operation_id;
                    $new->save();
                }
            }
        } else {
            $operationInfo = OperationInfo::where('id', $id)->first();
            if ($operationInfo->operation_id > 0) {
                $oldOperationID = $operationInfo->operation_id;
                $ss = OperationInfoSystem::where('new_operation_id', $oldOperationID)->get();

                foreach ($ss as $s) {
                    $recons = OperationInfoRecon::where('system_id', $s->system_id)->get();
                    foreach ($recons as $recon) {
                        $recon->system_id = null;
                        if ($recon->operation_info_recon_status_id == 4) {
                            $recon->operation_info_recon_status_id = 2;
                        } else {
                            $recon->operation_info_recon_status_id = 1;
                        }
                        $reconSystem = OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)->first();
                        $reconSystem->delete();
                        $recon->save();
                        operationReconSoloBcast($recon->id, 5);
                    }
                    $s->delete();
                }
                $operationInfo = OperationInfo::where('id', $id)->first();
                $operationInfo->operation_id = null;
                $operationInfo->save();
            }
        }



        operationInfoSoloPageBroadcast($id, 1);
        operationInfoOperationBcast($id, 9);
        operationInfoCampaignsBcast($id, 10);
        operationInfoSystemsBcast($id, 11);
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
        $operation = OperationInfo::where('link', $id)->first();
        $opID = $operation->id;
        $opFleets = OperationInfoFleet::where('operation_info_id', $opID)->get();
        foreach ($opFleets as $opFleet) {
            $opFleet->delete();
        }
        $opMessage = OperationInfoMessage::where('operation_info_id', $opID)->get();
        foreach ($opMessage as $opMessage) {
            $opMessage->delete();
        }
        $opRecons = OperationInfoRecon::where('operation_info_id', $opID)->get();
        foreach ($opRecons as $opRecon) {
            $opRecon->operation_info_id = null;
            $opRecon->operation_info_fleet_id = null;
            $opRecon->system_id = null;
            $opRecon->operation_info_recon_status_id = 1;
            $opRecon->operation_system_id = null;
            $opRecon->role_id = null;
            $opRecon->online = 0;
            $opRecon->dead = 0;
            $opRecon->save();
            $opSystemRecon = OperationInfoSystemRecon::where('operation_info_recon_id', $opRecon->id)->get();
            foreach ($opSystemRecon as $sRecon) {
                $sRecon->delete();
            }
        }
        $opSystems = OperationInfoSystem::where('operation_info_id', $opID)->get();
        foreach ($opSystems as $opSystem) {
            $opSystem->delete();
        }
        $operation->delete();
        operationInfoOver($opID, 15);
        operationInfoSoloDeleteBroadcast($opID, 3);
    }
}
