<?php

namespace App\Http\Controllers;

use App\Events\RcSheetUpdate;
use App\Models\Logging;
use App\Models\RcGsolUsers;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

class RcGsolUsersController extends Controller
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
        //
    }

    public function addGsoltoStation(Request $request, $id)
    {
        $gsolNameID = Station::where('id', $id)->value('rc_gsol_id');
        $gsolName = User::where('id', $gsolNameID)->value('name');
        Station::where('id', $id)->update(['rc_gsol_id' => $request->user_id]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));

        $text = Auth::user()->name . " Added to Gsol for the " . $message->name . " station ";
        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 23]);
        $log = $log->id;
        Helper::sheetlogs($log);
    }

    public function removeGsoltoStation($id)
    {
        $gsolNameID = Station::where('id', $id)->value('rc_gsol_id');
        $gsolName = User::where('id', $gsolNameID)->value('name');
        Station::where('id', $id)->update(['rc_gsol_id' => null]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        $text = Auth::user()->name . " Removed " . $gsolName . " from Gsol for the " . $message->name . " station ";
        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 24]);
        $log = $log->id;
        Helper::sheetlogs($log);
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
