<?php

namespace App\Http\Controllers;

use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use Illuminate\Http\Request;
use utils\Broadcasthelper\Broadcasthelper;

class NewUserNodeController extends Controller
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

    public function addTimer(Request $request, $id)
    {

        NewUserNode::where('id', $id)->update([
            "end_time" => $request->end_time,
            "input_time" => now(),
            "base_time" => $request->base_time
        ]);

        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
    }

    public function addTimerNonUsuer(Request $request, $id)
    {
        NewSystemNode::where('id', $id)->update([
            "end_time" => $request->end_time,
            "input_time" => now(),
            "base_time" => $request->base_time
        ]);

        Broadcasthelper::broadcastsystemSolo($request->system_id, 7);
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
