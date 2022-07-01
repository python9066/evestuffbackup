<?php

use App\Events\OperationInfoPageSoloUpdate;
use App\Events\OperationInfoPageUpdate;
use App\Models\OperationInfo;
use App\Models\OperationInfoDoctrine;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoMumble;
use App\Models\OperationInfoRecon;
use App\Models\OperationInfoUser;
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

     *
     * @param  int  $flagNumber
     * 1 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPageBroadcast($id, $flagNumber)
    {
        $message = operationInfoSoloPagePull($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $id
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
            'recons.main',
            'recons.system',
            'status'
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
            'recons.main',
            'recons.system',
            'status'
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
        return  OperationInfoDoctrine::all();
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
                return $new->id;
            }
        } else {

            $check = OperationInfoRecon::where('name', $name)->first();
            $check->operation_info_id = $opID;
            $check->save();
            operationReconSoloBcast($check->id, 5);
            return $check->id;
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
