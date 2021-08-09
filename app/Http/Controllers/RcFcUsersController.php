<?php

namespace App\Http\Controllers;

use App\Models\RcFcUsers;
use App\Models\RcStationRecords;
use App\Models\Station;
use Illuminate\Http\Request;

class RcFcUsersController extends Controller
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

    public function addFCtoStation(Request $request, $id)
    {
        $check = RcFcUsers::where('user_id', $request->user_id)->count();

        if ($check == 0) {
            $check =  RcFcUsers::Create(['user_id' => $request->user_id])->get();
        } else {
            $check = RcFcUsers::where('user_id', $request->user_id)->get();
        }
        dd($check['id']);
        $fcid = $check->id;
        Station::where('id', $id)->update(['fc_fc_id' => $fcid]);
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
