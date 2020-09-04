<?php

namespace App\Http\Controllers;

use App\NotificationRecords;
use App\Region;
use Illuminate\Http\Request;

class NotificationRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return ['notifications' => NotificationRecords::all()];
        $now = Now('-2 hours');
        return [ 'notifications' => NotificationRecords::where('timestamp','>=',$now)->get()];
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

    public function regionLink($region_id)
    {
        $now = Now('-2 hours');
        $http = "https://evemaps.dotlan.net/map/";
        $region = Region::where('id', $region_id)->get();
        foreach ($region as $region) {
            if ($region->region_name == "Period Basis") {

                $http = $http . "Period_Basis/";
            } else {

                $http = $http . $region->region_name . "/";
            }
        }
        $link = NotificationRecords::where('region_id', $region_id)->where('timestamp','>=',$now)->get()->pluck('system_name');
        $count = $link->count();
        // dd($count);
        if ($count == 0) {
            return ['link' => "nope"];
        }
        $link = $link->unique();
        // dd($link);
        foreach ($link as $link) {
            $http = $http . $link . ",";
        }
        $http = substr($http, 0, -1);
        $http = $http . "#adm";
        return ['link' => $http];
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

        // dd($request,$id);
        NotificationRecords::find($id)->update($request->all());
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
