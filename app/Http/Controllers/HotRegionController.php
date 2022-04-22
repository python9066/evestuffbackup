<?php

namespace App\Http\Controllers;

use App\Events\StationSheetUpdate;
use App\Models\HotRegion;
use utils\Notificationhelper\Notifications;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->can('edit_hot_region')) {
            $pullStart = HotRegion::where('update', 1)->pluck('region_id');
            $pull = Region::whereIn('id', $pullStart)->select(['region_name as text', 'id as value'])->get();
            $fcsStart = HotRegion::where('show_fcs', 1)->pluck('region_id');
            $fcs = Region::whereIn('id', $pullStart)->select(['region_name as text', 'id as value'])->get();
            $regionList = Region::whereNotNull('id')->select(['region_name as text', 'id as value'])->get();
            return [
                'pull' => $pull,
                'fcs' => $fcs,
                'regionlist' => $regionList,
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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


    public function updateSetting(Request $request)
    {
        $ids = [];
        $fc = $request->fc;
        $pull = $request->pull;
        foreach ($pull as $pull) {
            array_push($ids, $pull['value']);
        }





        HotRegion::whereNotNull('id')->update(['update' => 0, 'show_fcs' => 0]);
        HotRegion::whereIn('region_id', $ids)->update(['update' => 1]);
        HotRegion::whereIn('region_id', $fc)->update(['show_fcs' => 1]);

        $flag = collect([
            'flag' => 1
        ]);
        broadcast(new StationSheetUpdate($flag));

        $ids = HotRegion::where('update', 1)->pluck('region_id');

        foreach ($ids as $id) {
            $stations =  Notifications::reconRegionPull($id);
            foreach ($stations as $station) {
                Notifications::reconRegionPullIdCheck($station);
            }
        }

        $flag = collect([
            'flag' => 2
        ]);
        broadcast(new StationSheetUpdate($flag));
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
        $user = Auth::user();
        if ($user->can('edit_hot_region')) {
            HotRegion::where('id', $id)->update($request->all());
        }
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
