<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcSheetUpdate;
use App\Events\WelpSheetUpdate;
use App\Models\ChillStationRecords;
use App\Models\Logging;
use App\Models\RcReconUsers;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\User;
use App\Models\WelpStationRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

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
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update(['rc_recon_id' => $reconid]);
        }
        $message = Helper::StationRecordsSolo(4, $id);
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        $message = ChillStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new ChillSheetUpdate($flag));
        }

        $message = WelpStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new WelpSheetUpdate($flag));
        }
        $text = Auth::user()->name . " Added as Cyno";

        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 21]);
        $log = $log->id;
        Helper::sheetlogs($log);
        Helper::stationlogs($log);
    }

    public function removeRecontoStation($id)
    {
        $reconrcid = Station::where('id', $id)->value('rc_recon_id');
        $userid = RcReconUsers::where('id', $reconrcid)->value('user_id');
        $username = User::where('id', $userid)->value('name');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update(['rc_recon_id' => null]);
        }
        $message = Helper::StationRecordsSolo(4, $id);
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        $message = ChillStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new ChillSheetUpdate($flag));
        }
        $message = WelpStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new WelpSheetUpdate($flag));
        }
        $text = Auth::user()->name . " Removed " . $username . " As Cyno";

        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 22]);
        $log = $log->id;
        Helper::sheetlogs($log);
        Helper::stationlogs($log);
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
