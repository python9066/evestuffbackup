<?php

namespace utils\Broadcasthelper;

use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use utils\NewCampaignhelper\NewCampaignhelper;

class Broadcasthelper
{

    public static function broadcastsystemSolo($systemID, $flagNumber)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = NewCampaignhelper::systemSolo($systemID);

        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => $flagNumber,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }

    public static function broadcastuserSolo($opID, $opUserID, $flagNumber)
    {
        $message = NewCampaignhelper::opUserSolo($opID, $opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);

        broadcast(new OperationUpdate($flag));
    }

    public static function broadcastuserOwnSolo($opUserID, $userID, $flagNumber)
    {
        $message = NewCampaignhelper::ownUsersolo($opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'userid' => $opUserID,
            'id' => $userID,
            'message' => $message
        ]);

        broadcast(new OperationOwnUpdate($flag));
    }
}
