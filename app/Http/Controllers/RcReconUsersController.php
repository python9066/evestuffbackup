<?php

namespace App\Http\Controllers;

use App\Events\RcSheetUpdate;
use App\Models\RcReconUsers;
use App\Models\RcStationRecords;
use App\Models\Station;
use Illuminate\Http\Request;

class RcReconUsersController extends Controller
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

    public function addRecontoStation(Request $request, $id)
    {
        $check = RcReconUsers::where('user_id', $request->user_id)->count();

        if ($check == 0) {
            RcReconUsers::Create(['user_id' => $request->user_id])->get();
        } else {
            RcReconUsers::where('user_id', $request->user_id)->get();
        }

        $reconid = RcReconUsers::where('user_id', $request->user_id)->value('id');
        Station::where('id', $id)->update(['rc_recon_id' => $reconid]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
    }

    public function removeRecontoStation($id)
    {

        Station::where('id', $id)->update(['rc_recon_id' => null]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
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
