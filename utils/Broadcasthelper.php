<?php

namespace utils\Broadcasthelper;

use App\Events\CustomOperationPageUpdate;
use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use utils\NewCampaignhelper\NewCampaignhelper;

class Broadcasthelper
{

    public static function broadcastsystemSolo($systemID, $flagNumber)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaignOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');
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

    public static function broadcastCampaignSolo($campaignID, $opID, $flagNumber)
    {
        $message = NewCampaignhelper::campaignSolo($campaignID);
        $flag = collect([
            'flag' => $flagNumber,
            'id' => $opID,
            'message' => $message
        ]);

        broadcast(new OperationUpdate($flag));
    }

    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 6 = update op char table

     */



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

    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 3 = Add/Update Own Char info -
     * 5 = RemoveChar -

     */

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


    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 1 = Add Custom Operation To list -
     * 2 = Update Custom Operation On List -
     * 3 = Delete CUstom Operation from list -

     */

    public static function broadcastCustomOperationSolo($opID, $flagNumber)
    {
        $message = NewCampaignhelper::customOperationSolo($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new CustomOperationPageUpdate($flag));
    }


    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 3 = Delete CUstom Operation from list -

     */

    public static function broadcastCustomOperationDeleteSolo($opID, $flagNumber)
    {

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $opID,
        ]);
        broadcast(new CustomOperationPageUpdate($flag));
    }
}
