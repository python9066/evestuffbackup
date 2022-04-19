<?php

namespace App\Http\Controllers;

use App\Models\NewOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    'campaign.constellation:id,constellation_name',
                    'campaign.region:id,region_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get();
            return ['solooplist' => $data];
        } else {
            $data = NewOperation::where('solo', 1)
                ->with([
                    'campaign:id,solo,status,created_at,updated_at',
                    'campaign.constellation:id,constellation_name',
                    'campaign.region:id,region_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get();
            return ['solooplist' => $data];
        }
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
