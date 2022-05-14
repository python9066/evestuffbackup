<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\System;
use Illuminate\Http\Request;
use utils\Broadcasthelper\Broadcasthelper;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systemlist = [];
        $systems = System::all();
        foreach ($systems as $system) {
            $data = [];
            $data = [
                'text' => $system->system_name,
                'value' => $system->id
            ];

            array_push($systemlist, $data);
        }

        return ['systemlist' => $systemlist];
    }

    public function addScout(Request $request, $systemID)
    {
        System::where('id', $systemID)->update(['scout_id' => $request->user_id]);
        Broadcasthelper::broadcastsystemSolo($systemID, 7);
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

    public function systemsinconstellation($id)
    {
        $constid = Campaign::where('link', $id)->value('constellation_id');
        return ['systems' => System::where('constellation_id', $constid)->select('id', 'system_name')->get()];
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
