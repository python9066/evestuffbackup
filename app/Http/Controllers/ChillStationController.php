<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Models\ChillStationRecords;
use App\Models\Station;
use Illuminate\Http\Request;

class ChillStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ChillStationRecords::where('show_on_chill', 1)->get();
        return ['stations' => $data];
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

    public function chillSheetListRegion()
    {
        $data = [];
        $pull = ChillStationRecords::where('show_on_chill', 1)->get();
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

        return ['chillsheetlistRegion' => $data];
    }

    public function chillSheetListStatus()
    {
        $data = [];
        $pull = ChillStationRecords::where('show_on_chill', 1)->get();
        dd($pull);
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

        return ['chillsheetlistStatus' => $data];
    }


    public function stationdone($id)
    {
        Station::where('id', $id)->update(['show_on_chill' => 2, 'station_status_id' => 10, 'rc_fc_id' => null, 'rc_gsol_id' => null, 'rc_recon_id' => null, 'rc_id' => null, 'timer_image_link' => null, 'notes' => null]);
        $message = ChillStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new ChillSheetUpdate($flag));
    }


    public function chillSheetListType()
    {
        $data = [];
        $pull = ChillStationRecords::where('show_on_chill', 1)->get();
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

        return ['chillsheetlistType' => $data];
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
