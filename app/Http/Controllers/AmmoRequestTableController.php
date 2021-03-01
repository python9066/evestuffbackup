<?php

namespace App\Http\Controllers;

use App\Events\StationNotificationUpdate;
use App\Models\AmmoRequestTable;
use App\Models\Station;
use App\Models\StationRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmmoRequestTableController extends Controller
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
    public function store(Request $request)
    {
        $new = AmmoRequestTable::create($request->all());
        echo $new;
        $ammid = $new->id;
        $station = Station::where('id', $new->station_id)->first();
        $station->updated(['ammo_request_id' => $ammid]);
        $message = StationRecords::where('id', $new->station_id)->first();
        $flag = collect([
            'message' => $message
        ]);
        broadcast(new StationNotificationUpdate($flag));
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
