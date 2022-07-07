<?php

namespace App\Http\Controllers;

use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\OperationInfo;
use App\Models\OperationInfoSystem;
use Illuminate\Http\Request;
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
        }
    }


    public function editHackOperation(Request $request, $id)
    {
        if ($request->operation_id) {
            $operationInfo = OperationInfo::where('id', $id)->first();
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
                    $new->save();
                }
            }
        } else {
            $operationInfo = OperationInfo::where('id', $id)->first();
            $operationInfo->operation_id = null;
            $operationInfo->save();
            $systems = OperationInfoSystem::where('operation_info_id', $id)->get();
            foreach ($systems as $system) {
                $system->delete();
            }
        }

        operationInfoSoloPageBroadcast($id, 1);
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
