<?php

namespace utils\Broadcasthelper;

use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use utils\NewCampaignhelper\NewCampaignhelper;

class Broadcasthelper
{

    public static function broadcastsystemSolo($systemID, $flag)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = NewCampaignhelper::systemSolo($systemID);

        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => $flag,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }

    public static function broadcastuserSolo($opID, $opUserID, $flag)
    {
        $message = NewCampaignhelper::opUserSolo($opID, $opUserID);
        $flag = collect([
            // * 6 is to add newley made char to op list
            'flag' => $flag,
            'message' => $message,
            'id' => $opID
        ]);

        broadcast(new OperationUpdate($flag));
    }

    public static function broadcastuserOwnSolo($opUserID, $userID, $flag)
    {
        $message = NewCampaignhelper::ownUsersolo($opUserID);
        $flag = collect([
            'flag' => $flag,
            'userid' => $opUserID,
            'id' => $userID,
            'message' => $message
        ]);

        broadcast(new OperationOwnUpdate($flag));
    }
}
