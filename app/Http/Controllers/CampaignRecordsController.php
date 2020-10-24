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
        $pull = CampaignRecords::where('status_id',"<",3)->get();
        echo "hello";
        dd($pull);

        return ['campaigns' => CampaignRecords::all()];
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
