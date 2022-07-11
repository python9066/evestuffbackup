<?php

use App\Events\OperationInfoPageSoloUpdate;
use App\Events\OperationInfoPageUpdate;
use App\Models\OperationInfo;
use App\Models\OperationInfoDoctrine;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoMessage;
use App\Models\OperationInfoMumble;
use App\Models\OperationInfoRecon;
use App\Models\OperationInfoStatus;
use App\Models\OperationInfoUser;
use App\Models\System;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

if (!function_exists('operationInfoAll')) {
    function operationInfoAll()
    {
        $flag = collect([
            'flag' => 1,
            'message' => "fefefefe"
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
        return OperationInfo::with(['status'])->select('id', 'name', 'start', 'status_id', 'link')->get();
    }
}

if (!function_exists('operationInfoSolo')) {

    function operationInfoSolo($id)
    {
        return OperationInfo::where('id', $id)
            ->with(['status'])->select('id', 'name', 'start', 'status_id', 'link')->first();
    }
}



if (!function_exists('operationInfoSoloBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloBroadcast($id, $flagNumber)
    {
        $message =  operationInfoSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPageBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     * @param  int  $id
     * Op ID
     * @param  int  $flagNumber
     * 1 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPageBroadcast($id, $flagNumber)
    {
        $message = operationInfoSoloPagePullTopInfo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $id
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPagePullTopInfo')) {
    function operationInfoSoloPagePullTopInfo($id)
    {
        return  OperationInfo::where('id', $id)->first();
    }
}

if (!function_exists('operationInfoSoloPageMessage')) {
    function operationInfoPageMessage($id)
    {
        return  OperationInfoMessage::where('operation_info_id', $id)
            ->with('user:id,name,eve_user_id')
            ->orderBy('id', 'desc')
            ->get();
    }
}

if (!function_exists('operationInfoPageMessageBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 7 = Add/Update Solo Operation Info Message

     */
    function operationInfoPageMessageBroadcast($opID, $flagNumber)
    {
        $message = operationInfoPageMessage($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPagePull')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPagePull($id)
    {
        return  OperationInfo::where('id', $id)->with([
            'messages.user:id,name,eve_user_id',
            'fleets.fc',
            'fleets.boss',
            'fleets.mumble',
            'fleets.doctrine',
            'fleets.alliance',
            'fleets.recons.main:id,eve_user_id,name',
            'fleets.recons.fleetRole',
            'recons.main',
            'recons.status',
            'recons.system',
            'recons.fleet',
            'recons.fleetRole',
            'status',
            'systems:id,system_name,constellation_id,region_id',
            'systems.region:id,region_name',
            'systems.constellation:id,constellation_name',
            'campaigns',
            'operation'
        ])->first();
    }
}

if (!function_exists('operationInfoSoloPagePullLink')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPagePullLink($link)
    {
        return  OperationInfo::where('link', $link)->with([
            'messages.user:id,name,eve_user_id',
            'fleets.fc',
            'fleets.boss',
            'fleets.mumble',
            'fleets.doctrine',
            'fleets.alliance',
            'fleets.recons.main:id,eve_user_id,name',
            'fleets.recons.fleetRole',
            'recons.main',
            'recons.status',
            'recons.system',
            'recons.fleet',
            'recons.fleetRole',
            'status',
            'systems:id,system_name,constellation_id,region_id',
            'systems.region:id,region_name',
            'systems.constellation:id,constellation_name',
            'campaigns',
            'operation'
        ])->first();
    }
}


if (!function_exists('operationInfoUsersAll')) {

    function operationInfoUsersAll()
    {
        return  OperationInfoUser::all();
    }
}

if (!function_exists('operationInfoMumbleAll')) {

    function operationInfoMumbleAll()
    {
        return  OperationInfoMumble::all();
    }
}


if (!function_exists('operationInfoDoctrinesAll')) {

    function operationInfoDoctrinesAll()
    {
        return  OperationInfoDoctrine::orderBy("name")->get();
    }
}


if (!function_exists('operationInfoFleetSolo')) {

    function operationInfoFleetSolo($id)
    {
        return  OperationInfoFleet::where('id', $id)->with([
            'fc',
            'boss',
            'mumble',
            'doctrine',
            'alliance',
            'recons.main:id,eve_user_id,name',
            'recons.fleetRole',
        ])->first();
    }
}


if (!function_exists('operationInfoSoloPageFleetBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Fleet ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info Fleet

     */
    function operationInfoSoloPageFleetBroadcast($id, $opID, $flagNumber)
    {
        $message = operationInfoFleetSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPageFleetBroadcastDelete')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Fleet ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 6 = Remove Solo Operation Info Fleet

     */
    function operationInfoSoloPageFleetBroadcastDelete($id, $opID, $flagNumber)
    {

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $id,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}



if (!function_exists('operationInfoStatus')) {

    function operationInfoStatus($statusID)
    {
        return  OperationInfoStatus::where('id', $statusID)->first();
    }
}

if (!function_exists('operationInfoStatusBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Status ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 8 = Update Op Status

     */
    function operationInfoStatusBcast($id, $opID, $flagNumber)
    {
        $message =  operationInfoStatus($id);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}



if (!function_exists('operationInfoOperation')) {

    function operationInfoOperation($opID)
    {
        $op = OperationInfo::where('id', $opID)->first();
        return  $op->operation;
    }
}

if (!function_exists('operationInfoOperationBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 9 = add/remove Hack Operation

     */
    function operationInfoOperationBcast($opID, $flagNumber)
    {
        $message =  operationInfoOperation($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationInfoCampaigns')) {

    function operationInfoCampaigns($opID)
    {
        $op = OperationInfo::where('id', $opID)->first();
        return  $op->campaigns;
    }
}

if (!function_exists('operationInfoCampaignsBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 10 = Add/Remove Campaigns

     */
    function operationInfoCampaignsBcast($opID, $flagNumber)
    {
        $message =  operationInfoCampaigns($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationInfoSystems')) {

    function operationInfoSystems($opID)
    {
        //  'systems:id,system_name,constellation_id,region_id',
        //     'systems.region:id,region_name',
        //     'systems.constellation:id,constellation_name',
        $op = OperationInfo::where('id', $opID)->first();
        $systems = $op->systems()
            ->with([
                'region:id,region_name',
                'constellation:id,constellation_name'
            ])
            ->select(['systems.id', 'system_name', 'constellation_id', 'region_id'])
            ->get();
        return $systems;
    }
}

if (!function_exists('operationInfoSystemsBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 11 = add/remove systems
     *
     */
    function operationInfoSystemsBcast($opID, $flagNumber)
    {
        $message =  operationInfoSystems($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('checkUserName')) {

    function checkUserName($name, $opID)
    {
        $check = OperationInfoUser::where('name', $name)->count();
        if ($check == 0) {
            $userIds = collect();
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online python9066@gmail.com',
            ])
                ->withBody(json_encode([$name]), 'application/json')
                ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

            $res = $response->collect($key = null);
            foreach ($res as $key => $re) {
                if ($key == 'characters') {
                    $userIds->push($re[0]['id']);
                }
            }

            foreach ($userIds as $userID) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online python9066@gmail.com',
                ])->get('https://esi.evetech.net/latest/characters/' . $userID . '/?datasource=tranquility');
                $res = $response->collect($key = null);
                $new = new OperationInfoUser();
                $new->id = $userID;
                $new->alliance_id = $res['alliance_id'] ?? null;
                $new->corporation_id = $res['corporation_id'];
                $new->name = $res['name'];
                $new->url = 'https://images.evetech.net/characters/' . $userID . '/portrait?tenant=tranquility&size=64';
                $new->save();

                $message = operationInfoUsersAll();
                $flag = collect([
                    'flag' => 3,
                    'message' => $message,
                    'id' => $opID
                ]);
                broadcast(new OperationInfoPageSoloUpdate($flag));
                return $new->id;
            }
        } else {

            $check = OperationInfoUser::where('name', $name)->first();
            return $check->id;
        }
    }
}


if (!function_exists('checkUserNameRecon')) {

    function checkUserNameRecon($name, $opID)
    {
        $check = OperationInfoRecon::where('name', $name)->count();
        if ($check == 0) {
            $good = false;
            $userIds = collect();
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online python9066@gmail.com',
            ])
                ->withBody(json_encode([$name]), 'application/json')
                ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

            $res = $response->collect($key = null);
            foreach ($res as $key => $re) {
                if ($key == 'characters') {
                    $good = true;
                    $userIds->push($re[0]['id']);
                }
            }
            if (!$good) {
                return false;
            }
            foreach ($userIds as $userID) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online python9066@gmail.com',
                ])->get('https://esi.evetech.net/latest/characters/' . $userID . '/?datasource=tranquility');
                $res = $response->collect($key = null);
                $new = new OperationInfoRecon();
                $new->id = $userID;
                $new->user_id = Auth::id();
                $new->alliance_id = $res['alliance_id'] ?? null;
                $new->corp_id = $res['corporation_id'];
                $new->name = $res['name'];
                $new->url = 'https://images.evetech.net/characters/' . $userID . '/portrait?tenant=tranquility&size=64';
                $new->operation_info_id = $opID;
                $new->save();

                $message = operationReconAll();
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'id' => $opID
                ]);
                broadcast(new OperationInfoPageSoloUpdate($flag));
                operationReconSoloBcast($new->id, 5);
                return true;
            }
        } else {

            $check = OperationInfoRecon::where('name', $name)->first();
            $check->operation_info_id = $opID;
            $check->operation_info_fleet_id = null;
            $check->operation_info_recon_status_id = null;
            $check->save();
            operationReconSoloBcast($check->id, 5);
            return true;
        }
    }
}



if (!function_exists('operationReconAll')) {

    function operationReconAll()
    {
        $data = OperationInfoRecon::with([
            'main:id,name',
            'alliance:id,name,ticker,url',
            'corp:id,name,ticker,url',
            'system:id,system_name',
            'fleetRole',
            'fleet',
            'status'
        ])->get();
        return $data;
    }
}

if (!function_exists('operationReconSolo')) {

    function operationReconSolo($id)
    {
        $data = OperationInfoRecon::where('id', $id)->with([
            'main:id,name',
            'alliance:id,name,ticker,url',
            'corp:id,name,ticker,url',
            'system:id,system_name',
            'fleet',
            'fleetRole',
            'status'
        ])->first();
        return $data;
    }
}

if (!function_exists('operationReconSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Recon User ID
     *
     * @param  int  $flagNumber
     * 5 = Update Recon User

     */
    function operationReconSoloBcast($id, $flagNumber)
    {
        $message = operationReconSolo($id);
        $opID = $message->operation_info_id;
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationReconRemoveSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Recon User ID
     *
     *   @param  int  $opID
     * Operation info
     * @param  int  $flagNumber
     * 13 = remove Recon User from operation

     */
    function operationReconRemoveSoloBcast($id, $opID, $flagNumber)
    {
        $message = operationReconSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationDoctrineBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 12 = update fleet doctrins

     */
    function operationDoctrineBcast($flagNumber)
    {

        $OpIDs = OperationInfo::all();
        foreach ($OpIDs as $opID) {
            $id = $opID->id;
            $flag = collect([
                'flag' => $flagNumber,
                'id' => $id,
            ]);
            broadcast(new OperationInfoPageSoloUpdate($flag));
        }
    }
}
