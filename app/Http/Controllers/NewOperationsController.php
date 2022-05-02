<?php

namespace App\Http\Controllers;

use App\Models\NewCampaginOperation;
use App\Models\NewCampaign;
use App\Models\NewOperation;
use App\Models\OperationUser;
use App\Models\Region;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Campaignhelper\Campaignhelper;

class NewOperationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->can('access_campaigns')) {
            $data = NewOperation::where('solo', 1)
                ->with([
                    'campaign',
                    'campaign.status',
                    'campaign.constellation:id,constellation_name,region_id',
                    'campaign.constellation.region:id,region_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get();
        } else {
            $data = NewOperation::where('solo', 1)
                ->with([
                    'campaign:id,solo,status,created_at,updated_at',
                    'campaign.status',
                    'campaign.constellation:id,constellation_name,region_id',
                    'campaign.constellation.region:id,region_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get();
        }

        $regionIDs = collect();
        $regions = NewOperation::where('solo', 1)->with('campaign.constellation.region')->get();
        foreach ($regions as $r) {
            $regionIDs->push($r->campaign[0]->constellation->region->id);
        }

        $uRegionIDs = $regionIDs->unique();
        $regionList = Region::whereIn('id', $uRegionIDs)->select(['id as value', 'region_name as text'])->orderBy('region_name', 'asc')->get();


        return [
            'solooplist' => $data,
            'regionList' => $regionList
        ];
    }


    public function getInfo($id)
    {
        $data = NewOperation::where('link', $id)
            ->with([
                'campaign',
                'campaign.status',
                'campaign.constellation:id,constellation_name,region_id',
                'campaign.constellation.region:id,region_name',
                'campaign.alliance:id,name,ticker,standing,url,color',
                'campaign.system:id,system_name,adm',
            ])
            ->first();

        $operationsID = NewOperation::where('link', $id)->pluck('id');
        $campaignIDs = NewCampaginOperation::where('operation_id', $operationsID)->pluck('campaign_id');
        // $contellationIDs = NewCampaign::whereIn('id', $campaignIDs)->where('status_id', [2, 5])->pluck('20000646');
        $contellationIDs = NewCampaign::whereIn('id', $campaignIDs)->pluck('constellation_id');
        $contellationIDs =  $contellationIDs->unique();
        $systems = Campaignhelper::systemsAll($contellationIDs);
        $opUsers = Campaignhelper::opUserAll($operationsID);
        $ownChars = Campaignhelper::ownUserAll(Auth::id());

        return [
            'data' => $data,
            'systems' => $systems,
            'opUsers' => $opUsers,
            'ownChars' => $ownChars
        ];
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
