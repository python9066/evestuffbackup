<?php

namespace utils\Broadcasthelper;

use App\Events\CustomOperationPageUpdate;
use App\Events\OperationAdminUpdate;
use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\OperationUser;
use App\Models\OperationUserList;
use utils\NewCampaignhelper\NewCampaignhelper;

class Broadcasthelper
{

    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 6 = update op char table
     * 7 = update campaign system

     */

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
     * 1 = update operation user list,

     */



    public static function broadcastOperationUserList($opID, $flagNumber)
    {
        $userIDs = OperationUserList::where('operation_id', $opID)->pluck('user_id');
        $message = NewCampaignhelper::userListAll($userIDs, $opID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'message' => $message,
            'id' => $opID
        ]);

        broadcast(new OperationAdminUpdate($flag));
    }

    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 3 = Add/Update Own Char info -
     * 5 = RemoveChar -

     */

    public static function broadcastuserOwnSolo($opUserID, $userID, $flagNumber, $opID)
    {
        $message = NewCampaignhelper::ownUsersolo($opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'userid' => $opUserID,
            'id' => $userID,
            'message' => $message
        ]);

        broadcast(new OperationOwnUpdate($flag));
    }

    /**

     * Example of documenting multiple possible datatypes for a given parameter

     * @param int $flagNumber
     * 1 = Open Add Char overlay -
     *
     *  @param int $type
     * 1 = Normal message -
     * 2 = read only message

     */

    public static function broadcastAllCharOverlay($flagNumber, $opID, $type)
    {
        $users = NewCampaignhelper::openAddUserOverlay($opID);
        foreach ($users as $user) {

            $userID = $user->user_id;

            $flag = collect([
                'flag' => $flagNumber,
                'op_id' => $opID,
                'id' => $userID,
                'type' => $type
            ]);

            broadcast(new OperationOwnUpdate($flag));
        }
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
