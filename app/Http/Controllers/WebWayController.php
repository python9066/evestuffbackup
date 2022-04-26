<?php

namespace App\Http\Controllers;

use App\Jobs\getWebwayJob;
use App\Listeners\SendStationSheetUpdateWebway;
use App\Models\WebWay;
use Illuminate\Http\Request;

class WebWayController extends Controller
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

    public function getWebWay(Request $request)
    {
        $system_id = $request['system_id'];
        $link = $request['link'];
        $jumps = $request['jumps'];
        $link_p = $request['link_p'];
        $jumps_p = $request['jumps_p'];
        $start_system_id = $request['start_system_id'];
        $send = false;

        $old = WebWay::where([
            'start_system_id' => $start_system_id,
            'system_id' => $system_id,
            'permissions' => 0
        ])->first();

        $oldp = WebWay::where([
            'start_system_id' => $start_system_id,
            'system_id' => $system_id,
            'permissions' => 1
        ])->first();

        getWebwayJob::dispatch(
            $start_system_id,
            $system_id,
            $link,
            $jumps,
            $link_p,
            $jumps_p
        )->onQueue('webway');





        if ($old) {
            if ($old->webway != $link || $old->jumps != $jumps) {
                $send = true;
            }
        } else {
            $send = true;
        };

        if ($oldp) {
            if ($oldp->webway != $link_p || $oldp->jumps != $jumps_p) {
                $send = true;
            }
        } else {
            $send = true;
        };

        // if ($send) {
        //     $system = $system_id;
        //     $flag = collect([
        //         'id' => $system
        //     ]);

        //     broadcast(new SendStationSheetUpdateWebway($flag));
        // }

        $system = $system_id;
        $flag = collect([
            'id' => $system
        ]);

        broadcast(new SendStationSheetUpdateWebway($flag));
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
