<?php

use App\Models\Alliance;
use App\Models\AllianceStationWatchList;
use App\Models\Constellation;
use App\Models\ConstellationStationWatchList;
use App\Models\HotRegion;
use App\Models\Item;
use App\Models\ItemStationWatchList;
use App\Models\Region;
use App\Models\RegionStationWatchList;
use App\Models\RoleStationWatchList;
use App\Models\Station;
use App\Models\StationStationWatchList;
use App\Models\SystemStationWatchList;
use App\Models\StationWatchList;
use App\Models\System;
use App\Models\User;
use App\Models\UserStationWatchList;
use App\Models\WebWayStartSystem;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

if (!function_exists('allWatchList')) {
    function allWatchList()
    {
        $watchList = StationWatchList::with([
            "stations:id,name",
            "systems:id,system_name",
            "constellations:id,constellation_name",
            "regions:id,region_name",
            "roles:id,name",
            "users:id,name",
            "alliances:id,ticker",
            "items:id,item_name",
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
            "users:id,name",
            "alliances:id,ticker",
            "items:id,item_name",
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
        $alliance = AllianceStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('alliance_id');
        $item = ItemStationWatchList::whereIn('station_watch_list_id', $watchlist)->pluck('item_id');

        $station_query = Station::query();
        $station_query->join('systems', 'stations.system_id', '=', 'systems.id');
        $station_query->whereIn('stations.id', $station);
        $station_query->orWhereIn('stations.system_id', $system);
        $station_query->orWhereIn('systems.constellation_id', $constellation);
        $station_query->orWhereIn('systems.region_id', $region);

        if (count($alliance)) {
            $station_query->whereHas(
                'alliance',
                function ($query) use ($alliance) {
                    $query->whereIn('alliances.id', $alliance);
                }
            );
        }
        if (count($item)) {
            $station_query->whereIn('stations.item_id', $item);
        }

        $stations =
            $station_query->pluck('stations.id')
            ->unique()
            ->values();




        $data = StationRecords(6, $stations);
        return $data;
    }
}


if (!function_exists('getStationWatchListIDs')) {
    function getStationWatchListIDs($stationID)
    {
        $listByAlliance = null;
        $station = Station::whereId($stationID)->first();
        $listBySstationId = StationStationWatchList::where('station_id', $stationID)->pluck('station_watch_list_id');
        $listByRegionID = RegionStationWatchList::where('region_id', $station->system->region_id)->pluck('station_watch_list_id');
        $listByConstellationID = ConstellationStationWatchList::where('constellation_id', $station->system->constellation_id)->pluck('station_watch_list_id');
        $listBySystemID = SystemStationWatchList::where('system_id', $station->system_id)->pluck('station_watch_list_id');
        if ($station->corp->alliance) {
            $listByAlliance = AllianceStationWatchList::where('alliance_id', $station->corp->alliance->id)->pluck('station_watch_list_id');
        }
        $listByItemID = ItemStationWatchList::where('item_id', $station->item_id)->pluck('station_watch_list_id');

        $listIDs = array_merge(
            $listBySstationId->toArray(),
            $listByRegionID->toArray(),
            $listByConstellationID->toArray(),
            $listBySystemID->toArray(),
            $listByAlliance->toArray(),
            $listByItemID->toArray()
        );
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

if (!function_exists('getGtationWatchListNeededInfo')) {
    function getGtationWatchListNeededInfo()
    {
        $user = Auth::user();
        if ($user->can('view_station_watch_list_setup')) {
            $pullStart = HotRegion::where('update', 1)->pluck('region_id');
            $pull = Region::whereIn('id', $pullStart)
                ->orderBy('region_name', 'asc')
                ->select(['region_name as text', 'id as value'])
                ->get();

            $webwayStart = WebWayStartSystem::where('system_id', '!=', 30004759)
                ->pluck('system_id');
            $webway = System::whereIn('id', $webwayStart)
                ->orderBy('system_name', 'asc')
                ->select(['system_name as text', 'id as value'])
                ->get();

            $regionDropDownList = Region::whereIn('id', $pullStart)
                ->orderBy('region_name', 'asc')
                ->select(['region_name as text', 'id as value'])
                ->get();

            $regionList = Region::whereNotNull('id')
                ->orderBy('region_name', 'asc')
                ->select(['region_name as text', 'id as value'])
                ->get();


            $systemDropDownList = System::whereIn('region_id', $pullStart)
                ->orderBy('system_name', 'asc')
                ->select(['system_name as text', 'id as value'])
                ->get();

            $systemList = System::whereNotNull('id')
                ->orderBy('system_name', 'asc')
                ->select(['system_name as text', 'id as value'])
                ->get();


            $constellationDropDownList = Constellation::whereIn('region_id', $pullStart)
                ->orderBy('constellation_name', 'asc')
                ->select(['constellation_name as text', 'id as value'])
                ->get();



            $stationList = Station::join('systems', 'stations.system_id', '=', 'systems.id')
                ->whereIn('systems.region_id', $pullStart)
                ->where('stations.added_from_recon', 1)
                ->orderBy('name', 'asc')
                ->select(['name as text', 'stations.id as value'])->get();

            $roles = Role::whereNot('name', 'Super Admin')->orderBy('name', 'asc')->get();

            $corpIDs = Station::join('systems', 'stations.system_id', '=', 'systems.id')
                ->whereIn('systems.region_id', $pullStart)
                ->where('stations.added_from_recon', 1)
                ->pluck('corp_id');

            $corpIDs = $corpIDs->unique();

            $allianceList = Alliance::whereIn('id', function ($query) use ($corpIDs) {
                $query->select('alliance_id')
                    ->from('corps')
                    ->whereIn('id', $corpIDs);
            })->orderBy('ticker', 'asc')->select(['ticker as text', 'id as value'])->get();


            $itemIDs = Station::join('systems', 'stations.system_id', '=', 'systems.id')
                ->whereIn('systems.region_id', $pullStart)
                ->where('stations.added_from_recon', 1)
                ->pluck('item_id');

            $itemIDs = $itemIDs->unique();

            $items = Item::whereIn('id', $itemIDs)
                ->select(['item_name as text', 'id as value'])
                ->orderBy('text', 'asc')
                ->get();





            $roleList = $roles->map(function ($items) {
                $data['value'] = $items->id;
                $data['text'] = $items->name;
                $data['selected'] = false;

                return $data;
            });

            $userList = User::select('id', 'name')->where('id', '>', 5)->orderBy("name")->get();


            return [
                'pull' => $pull,
                'webwayStartSystems' => $webway,
                'regionlist' => $regionList,
                'systemlist' => $systemList,
                'stationlist' => $stationList,
                'constellationDropDownlist' => $constellationDropDownList,
                'regiondropdownlist' => $regionDropDownList,
                'systemdropdownlist' => $systemDropDownList,
                'allianceList' => $allianceList,
                'roles' => $roleList,
                'userList' => $userList,
                'itemList' => $items
            ];
        }
    }
}
