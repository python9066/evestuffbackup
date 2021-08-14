<?php

namespace App\Http\Controllers;

use App\Models\FleetType;
use App\Models\KeyFleetJoin;
use App\Models\KeyType;
use Illuminate\Http\Request;

class FleetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['fleets' => FleetType::all()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        FleetType::create($request->all());
    }

    public function getAllKeyFleets()
    {
        return ['keyfleets' => KeyType::with('fleets')->select('id', 'name')->get()];
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
        FleetType::find($id)->delete();
        KeyFleetJoin::where('fleet_type_id', $id)->delete();
    }
}
