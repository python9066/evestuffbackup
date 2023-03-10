<?php

use App\Models\ConstellationStationWatchList;
use App\Models\RegionStationWatchList;
use App\Models\RoleStationWatchList;
use App\Models\Station;
use App\Models\StationStationWatchList;
use App\Models\SystemStationWatchList;
use App\Models\StationWatchList;
use App\Models\User;
use App\Models\UserStationWatchList;
use Illuminate\Support\Facades\Auth;

if (!function_exists('allWatchList')) {
    function allWatchList()
    {
        $watchList = StationWatchList::with([
            "stations:id,name",
            "systems:id,system_name",
            "constellations:id,constellation_name",
            "regions:id,region_name",
            "roles:id,name",
            "users:id,name"
        ])
            ->get();

        return $watchList;
    }
}

if (!function_exists('allWatchListSolo')) {

    function allWatchListSolo($id)
    {
        $watchList = StationWatchList::whereId($id)->with([
            "stations:id,name",
            "systems:id,system_name",
            "constellations:id,constellation_name",
            "regions:id,region_name",
            "roles:id,name",
            "users:id,name"
        ])
            ->first();

        return $watchList;
    }
}

if (!function_exists('getAllallowedStations')) {
    function getAllallowedStations()
    {
        $user = User::whereId(Auth::id())->first();
        $roleIDs = $user->roles()->pluck('id');

        if (!$roleIDs->contains(2)) {
            $roleList = RoleStationWatchList::whereIn('role_id', $roleIDs)->pluck('station_watch_list_id');
            $userList = UserStationWatchList::where('user_id', Auth::id())->pluck('station_watch_list_id');

            $merge = $roleList->merge($userList);
            $watchlist = StationWatchList::whereIn('id', $merge)->where('active', true)->pluck('id');
        } else {
            $watchlist = StationWatchList::where('active', true)->pluck('id');
        }



        $station = StationStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('station_id');
        $system = SystemStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('system_id');
        $constellation = ConstellationStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('constellation_id');
        $region = RegionStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('region_id');

        $stations = Station::join('systems', 'stations.system_id', '=', 'systems.id')
            ->whereIn('stations.id', $station)
            ->orWhereIn('stations.system_id', $system)
            ->orWhereIn('systems.constellation_id', $constellation)
            ->orWhereIn('systems.region_id', $region)
            ->pluck('stations.id')
            ->unique()
            ->values();




        $data = StationRecords(6, $stations);
        return $data;
    }
}


if (!function_exists('getStationWatchListIDs')) {
    function getStationWatchListIDs($stationID)
    {

        $station = Station::whereId($stationID)->first();
        $listBySstationId = StationStationWatchList::where('station_id', $stationID)->pluck('station_watch_list_id');
        $listByRegionID = RegionStationWatchList::where('region_id', $station->system->region_id)->pluck('station_watch_list_id');
        $listByConstellationID = ConstellationStationWatchList::where('constellation_id', $station->system->constellation_id)->pluck('station_watch_list_id');
        $listBySystemID = SystemStationWatchList::where('system_id', $station->system_id)->pluck('station_watch_list_id');

        $listIDs = array_merge($listBySstationId->toArray(), $listByRegionID->toArray(), $listByConstellationID->toArray(), $listBySystemID->toArray());
        return $listIDs;
    }
}

if (!function_exists('getUsersWatchLists')) {
    function getUsersWatchLists()
    {
        $user = User::whereId(Auth::id())->first();
        $roleIDs = $user->roles()->pluck('id');

        if (!$roleIDs->contains(2)) {
            $roleList = RoleStationWatchList::whereIn('role_id', $roleIDs)->pluck('station_watch_list_id');
            $userList = UserStationWatchList::where('user_id', Auth::id())->pluck('station_watch_list_id');

            $merge = $roleList->merge($userList);
            $watchlist = StationWatchList::whereIn('id', $merge)->where('active', true)->pluck('id');
        } else {
            $watchlist = StationWatchList::where('active', true)->pluck('id');
        }

        $lists = StationWatchList::whereIn('id', $watchlist)->whereActive(1)->select('id', 'name')->get();

        return $lists;
    }
}
