<?php

namespace utils\Broadcasthelper;

use App\Events\OperationUpdate;
use App\Models\NewCampaginOperation;
use App\Models\NewCampaignSystem;
use utils\NewCampaignhelper\NewCampaignhelper;

class Broadcasthelper
{

    public static function broadcastsystemSolo($systemID)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaginOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
        $message = NewCampaignhelper::systemSolo($systemID);

        foreach ($obIDS as $op) {

            $flag = collect([
                'flag' => 7,
                'message' => $message,
                'id' => $op
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }
}
