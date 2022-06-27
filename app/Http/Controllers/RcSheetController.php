<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetMessageUpdate;
use App\Events\ChillSheetUpdate;
use App\Events\RcSheetMessageUpdate;
use App\Events\RcSheetUpdate;
use App\Events\StationSheetMessageUpdate;
use App\Events\WelpSheetMessageUpdate;
use App\Events\WelpSheetUpdate;
use App\Models\Alliance;
use App\Models\ChillStationRecords;
use App\Models\Corp;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\WelpStationRecords;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class RcSheetController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (Auth::user()->can("use_reserved_connection")) {
        //     return ['stations' => RcStationRecords::where('show_on_rc', 1)->with([
        //         'webway' => function ($t) {
        //             $t->where('permissions', 1);
        //         }
        //     ])->get()];
        // } else {
        //     return ['stations' => RcStationRecords::where('show_on_rc', 1)->with([
        //         'webway' => function ($t) {
        //             $t->where('permissions', 0);
        //         }
        //     ])->get()];
        // }

        return ['stations' => StationRecords(4)];
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

        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update(['corp_id' => $corpid, 'alliance_id' => $allianceid]);
        }

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));
    }

    public function fixalliance(Request $request, $id)
    {
        $alliance = Alliance::where('id', $request->allianceid)->first();
        $allianceid = $alliance->id;
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update(['alliance_id' => $allianceid]);
        }

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));
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
                'text' => $pull['item_name'],
                'value' => $pull['item_id'],
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
            $text = str_replace('Upcoming - ', '', $pull['status_name']);
            $data1 = [];
            $data1 = [
                'text' => $text,
                'value' => $pull['status_id'],
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistStatus' => $data];
    }

    public function updateMessage(Request $request, $id)
    {
        // dd($request->notes);

        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update($request->all());
        }

        $message = RcStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new RcSheetMessageUpdate($flag))->toOthers();
        }

        $message = ChillStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new ChillSheetMessageUpdate($flag))->toOthers();
        }

        $message = WelpStationRecords::where('id', $id)->first();
        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new WelpSheetMessageUpdate($flag))->toOthers();
        }

        $message = StationRecordsSolo(6, $id);
        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new StationSheetMessageUpdate($flag))->toOthers();
        }
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
                'text' => $pull['region_name'],
                'value' => $pull['region_id'],
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistRegion' => $data];
    }

    public function stationdone($id)
    {
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update([
                'show_on_rc' => 2,
                'station_status_id' => 10,
                'rc_fc_id' => null,
                'rc_gsol_id' => null,
                'rc_recon_id' => null,
                'rc_id' => null,
                'timer_image_link' => null,
                'notes' => null,
            ]);
        }
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
