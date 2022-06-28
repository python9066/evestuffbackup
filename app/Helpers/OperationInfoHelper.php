<?php

use App\Events\OperationInfoPageSoloUpdate;
use App\Events\OperationInfoPageUpdate;
use App\Models\OperationInfo;

if (!function_exists('operationInfoAll')) {
    function operationInfoAll()
    {
        $flag = collect([
            'flag' => 1,
            'message' => "fefefefe"
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
        return OperationInfo::with(['status'])->select('id', 'name', 'start', 'status')->get();
    }
}

if (!function_exists('operationInfoSolo')) {

    function operationInfoSolo($id)
    {
        return OperationInfo::where('id', $id)
            ->with(['status'])->select('id', 'name', 'start', 'status')->first();
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
        return  OperationInfo::where('id', $id)->with(['messages.user:id,name,eve_user_id'])->first();
    }
}
