<?php

use App\Models\Tower;

if (!function_exists('towerRecordAll')) {
    function towerRecordAll()
    {
        $data = Tower::with([
            'corp.alliance',
            'moon.system:region_id,constellation_id,id,system_name',
            'moon.region',
            'moon.constellation',
            'item',
            'status',
            'user:id,name,eve_user_id'
        ])->get();
        return $data;
    }
}

/**
 * Get Single Tower reconrd with joins

 *
 * @param  int  $id = TowerID
 * station id
 */

if (!function_exists('towerRecordSolo')) {
    function towerRecordSolo($id)
    {
        $data = Tower::whereId($id)->with([
            'corp.alliance',
            'moon.system:region_id,constellation_id,id,system_name',
            'moon.region',
            'moon.constellation',
            'item',
            'status',
            'user:id,name,eve_user_id'
        ])->get();
        return $data;
    }
}
