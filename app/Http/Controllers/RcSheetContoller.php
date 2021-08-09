<?php

namespace App\Http\Controllers;

use App\Events\RcSheetUpdate;
use App\Models\RcStationRecords;
use App\Models\Station;
use Illuminate\Http\Request;

class RcSheetContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['stations' => RcStationRecords::all()];
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

    public function rcSheetListRegion()
    {
        $data = [];
        $pull = Station::where('show_on_rc', 1)->get();
        $pull = $pull->unique('region_id');
        $pull = $pull->sortBy('region');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                "text" => $pull['region'],
                "value" => $pull['region_id']
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['rcsheetlistRegion' => $data];
    }

    public function stationdone($id)
    {
        Station::where('id', $id)->update(['show_on_rc' => 2, 'station_status_id' => 5, 'rc_fc_id' => null, 'rc_gsol_id' => null, 'rc_recon_id' => null]);
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
