<?php

namespace App\Http\Controllers;

use App\Events\RcSheetUpdate;
use App\Models\Logging;
use App\Models\RcFcUsers;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

class RcFcUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fclist = [];
        $fcs = RcFcUsers::all();
        foreach ($fcs as $fc) {
            $data = [];

            $name = User::where('id', $fc->user_id)->value('name');


            $data = [
                'id' => $fc->id,
                'user_id' => $fc->user_id,
                'fleet' => $fc->fleet,
                'name' => $name
            ];

            array_push($fclist, $data);
        }
        return ['fcs' => $fclist];
    }

    public function newfc(Request $request)
    {

        $check = User::where('name', $request->char_name)->count();
        // dd($check);
        if ($check == null) {
            $id = User::where('id', '>', 9999999999)->max('id');
            if ($id == null) {
                $id = 10000000000;
            } else {
                $id = $id + 1;
            }


            $new = User::Create(['name' => $request->char_name]);
            $new->update(['id' => $id]);
        } else {
            $id = User::where('name', $request->char_name)->value('id');
        }
        RcFcUsers::Create(['user_id' => $id]);
        $flag = collect([
            'flag' => 3
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

    public function addFCtoStation(Request $request, $id)
    {
        $check = RcFcUsers::where('user_id', $request->user_id)->count();

        if ($check == 0) {
            RcFcUsers::Create(['user_id' => $request->user_id])->get();
        } else {
            RcFcUsers::where('user_id', $request->user_id)->get();
        }

        $fcid = RcFcUsers::where('user_id', $request->user_id)->value('id');
        $userid = RcFcUsers::where('user_id', $request->user_id)->value('user_id');
        Station::where('id', $id)->update(['rc_fc_id' => $fcid]);

        $fcname = User::where('id', $userid)->value('name');

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));

        $text = Auth::user()->name . " Added" . $fcname . " to FC for " . $message->name;

        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 19]);
        $log = $log->id;
        // dd($log);
        Helper::sheetlogs($log);
    }

    public function addFCadd(Request $request, $id)
    {
        Station::where('id', $id)->update($request->all());
        $userid = RcFcUsers::where('id', $request->rc_fc_id)->value('user_id');
        $userName = User::where('id', $userid)->value('name');

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));

        $text = Auth::user()->name . " Added " . $userName . " as FC for " . $message->name;
        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 19]);
        $log = $log->id;
        Helper::sheetlogs($log);
    }

    public function removeFCtoStation($id)
    {
        $fcid = Station::where('id', $id)->value('rc_fc_id');
        $userid = RcFcUsers::where('id', $fcid)->value('user_id');
        $userName = User::where('id', $userid)->value('name');
        Station::where('id', $id)->update(['rc_fc_id' => null]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        $text = Auth::user()->name . " Removed " . $userName . " as FC for " . $message->name;
        $log = Logging::Create(['station_id' => $id, 'user_id' => Auth::id(), 'text' => $text, 'logging_type_id' => 20]);
        $log = $log->id;
        Helper::sheetlogs($log);
    }

    public function removeFC($id)
    {
        $user_id = RcFcUsers::where('id', $id)->value('user_id');
        // dd($fc);
        if ($user_id > 9999999999) {
            User::where('id', $user_id)->delete();
        }
        Station::where('rc_fc_id', $id)->update(['rc_fc_id' => 0]);
        RcFcUsers::where('id', $id)->delete();
        $flag = collect([
            'flag' => 2
        ]);
        broadcast(new RcSheetUpdate($flag));
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
