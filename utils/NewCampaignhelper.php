<?php

namespace utils\NewCampaignhelper;

use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use App\Models\OperationUser;
use App\Models\System;
use Illuminate\Support\Facades\Auth;

class NewCampaignhelper
{



    public static function ownUserAll()
    {
        return OperationUser::where('user_id', Auth::id())
            ->with(['userrole'])
            ->get();
    }

    public static function ownUsersolo($id)
    {
        return OperationUser::where('id', $id)
            ->with(['userrole'])
            ->first();
    }

    public static function opUserAll($opID)
    {
        return  OperationUser::where('operation_id', $opID)
            ->with([
                'user:id,name',
                "userrole",
                "userstatus",
                "system",
                "node"
            ])
            ->get();
    }

    public static function opUserSolo($opID, $id)
    {
        return OperationUser::where('operation_id', $opID)
            ->where('id', $id)
            ->with([
                'user:id,name',
                "userrole",
                "userstatus",
                "system",
                "node"
            ])
            ->first();
    }

    public static function systemsAll($contellationIDs)
    {
        return System::whereIn('constellation_id', $contellationIDs)
            ->with([
                'newCampaigns',
                'newNodes.nodeStatus',
                'newNodes.opUsers' => function ($q) {
                    $q->where('primery', 1);
                },
                'newNodes.opUsers.user:id,name',
                'newNodes.opUsers.userrole',
                'newNodes.opUsers.userstatus',
                'newNodes.system'
            ])
            ->get();
    }

    public static function systemSolo($systemID)
    {
        return System::where('id', $systemID)
            ->with([
                'newCampaigns',
                'newNodes.nodeStatus',
                'newNodes.opUsers' => function ($q) {
                    $q->where('primery', 1);
                },
                'newNodes.opUsers.user:id,name',
                'newNodes.opUsers.userrole',
                'newNodes.opUsers.userstatus',
                'newNodes.system'
            ])
            ->first();
    }
}
