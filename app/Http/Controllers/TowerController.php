<?php

namespace App\Http\Controllers;

use App\Models\Constellation;
use App\Models\Item;
use App\Models\Moon;
use App\Models\Region;
use App\Models\System;
use App\Models\Tower;
use Illuminate\Http\Request;

class TowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return ['towers' => towerRecordAll()];
    }

    public function towerFilters()
    {
        $regionIDs = Moon::has('towers')->pluck('region_id');
        $regionList = Region::whereIn('id', $regionIDs)->get();
        $region = $regionList->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->region_name;

            return $data;
        });



        $systemsIDs = Moon::has('towers')->pluck('system_id');
        $systemList = System::whereIn('id', $systemsIDs)->get();
        $systems = $systemList->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->system_name;

            return $data;
        });


        $constellationIDs = Moon::has('towers')->pluck('constellation_id');
        $constellationList = Constellation::whereIn('id', $constellationIDs)->get();
        $constellations = $constellationList->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->constellation_name;

            return $data;
        });

        $typeIDs = [
            12235, 20059, 20060, 27539, 27530,
            27589, 27592, 16213, 20061,
            20062, 27532, 27591, 27594,
            27540, 27609, 27612, 27535,
            27597, 27600, 25375, 12236,
            20063, 20064, 27533, 27595,
            27598, 16214, 20065, 20066,
            22709, 27780, 27782, 27784,
            27536, 27601, 27604, 27538,
            27603, 27606, 27786, 27788, 27790
        ];
        $towerTypes = Item::whereIn('id', $typeIDs)->get();
        $list = $towerTypes->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->item_name;

            return $data;
        });

        return [
            'towerList' => $list,
            'towerRegion' => $region,
            'towerSystems' => $systems,
            'towerConstellation' => $constellations
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $new = new Tower();
        $new->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tower = towerRecordSolo($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tower = Tower::whereId($id)->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
