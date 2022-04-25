<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\WebWay;
use App\Models\WebWayStartSystem;
use Illuminate\Http\Request;

class WebWayStartSystemsContorller extends Controller
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

    public function getSystemList()
    {
        $webwaySystemIDs = WebWayStartSystem::whereNotNull('id')->pluck('system_id');
        if ($webwaySystemIDs) {
            $systems = System::whereIn('id', $webwaySystemIDs)->select(['id as value', 'system_name as text'])->get();
            return ['systems' => $systems];
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
        WebWayStartSystem::create(['system_id' => $request->system_id]);
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
        WebWayStartSystem::where('system_id', $id)->delete();
    }
}
