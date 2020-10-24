<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignRecords;
use Illuminate\Http\Request;

class CampaignRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['campaigns' => CampaignRecords::all()];
    }

    public function campaignslist()
    {
        $data = [];
        $pull = CampaignRecords::where('status_id',"<",3)->get();
        foreach($pull as $pull){
            $data1 = [];
            $data1= [
                "text" => $pull['region'] ." - ". $pull['constellation']. " - ". $pull['system']. " - " .$pull['alliance']. " - " .$pull['item_name'],
                'value' => $pull['id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['campaignslist' => $data];
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
        return ['campaign' => Campaign::where('id',$id)];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request#
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        CampaignRecords::find($id)->update($request->all());
        // $notifications = NotificationRecords::find($id);
        // if($notifications->status_id != 10){
        // broadcast(new NotificationChanged($notifications))->toOthers();
        // }
        // broadcast(new NotificationChanged($notifications));
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
