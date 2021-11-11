<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcSheetMessageUpdate;
use App\Events\RcSheetUpdate;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\RcStationRecords;
use App\Models\Station;
use Illuminate\Http\Request;

class RcSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['stations' => RcStationRecords::where('show_on_rc', 1)->get()];
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

    public function fixcorp(Request $request, $id)
    {
        $corp = Corp::where('id', $request->corpid)->first();
        $corpid = $corp->id;
        $allianceid = $corp->alliance_id;

        Station::where('id', $id)->update(['corp_id' => $corpid, 'alliance_id' => $allianceid]);

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
    }

    public function fixalliance(Request $request, $id)
    {
        $alliance = Alliance::where('id', $request->allianceid)->first();
        $allianceid = $alliance->id;
        Station::where('id', $id)->update(['alliance_id' => $allianceid]);

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
    }




    public function rcSheetListType()
    {
        $data = [];
        $pull = RcStationRecords::where('show_on_rc', 1)->get();
        $pull = $pull->unique('item_id');
        $pull = $pull->sortBy('item_name');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                "text" => $pull['item_name'],
                "value" => $pull['item_id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistType' => $data];
    }

    public function rcSheetListStatus()
    {
        $data = [];
        $pull = RcStationRecords::where('show_on_rc', 1)->get();
        $pull = $pull->unique('status_id');
        $pull = $pull->sortBy('status_name');
        foreach ($pull as $pull) {
            $text = str_replace("Upcoming - ", "", $pull['status_name']);
            $data1 = [];
            $data1 = [
                "text" => $text,
                "value" => $pull['status_id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistStatus' => $data];
    }

    public function updateMessage(Request $request, $id)
    {
        // dd($request->notes);

        Station::where('id', $id)->update($request->all());

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $id
        ]);

        // dd($request, $id, $flag);
        broadcast(new RcSheetMessageUpdate($flag))->toOthers();
    }


    public function rcSheetListRegion()
    {
        $data = [];
        $pull = RcStationRecords::where('show_on_rc', 1)->get();
        $pull = $pull->unique('region_id');
        $pull = $pull->sortBy('region_name');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                "text" => $pull['region_name'],
                "value" => $pull['region_id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistRegion' => $data];
    }

    public function stationdone($id)
    {
        Station::where('id', $id)->update(['show_on_rc' => 2, 'station_status_id' => 10, 'rc_fc_id' => null, 'rc_gsol_id' => null, 'rc_recon_id' => null, 'rc_id' => null, 'timer_image_link' => null, 'notes' => null]);
        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
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
