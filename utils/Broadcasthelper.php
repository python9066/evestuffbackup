<?php

namespace utils\Broadcasthelper;

use App\Events\CustomOperationPageUpdate;
use App\Events\OperationAdminUpdate;
use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\OperationUserList;

class Broadcasthelper
{
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 6 = update op char table
     * 7 = update campaign system
     */
    public static function broadcastsystemSolo($systemID, $flagNumber)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaignOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');

        foreach ($obIDS as $op) {
            $message = systemSolo($systemID, $campaignIDs);
            $flag = collect([
                'flag' => $flagNumber,
                'message' => $message,
                'id' => $op,
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }

    public static function broadcastCampaignSolo($campaignID, $opID, $flagNumber)
    {
        $message = campaignSolo($campaignID);
        $flag = collect([
            'flag' => $flagNumber,
            'id' => $opID,
            'message' => $message,
        ]);

        broadcast(new OperationUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 6 = update op char table
     */
    public static function broadcastuserSolo($opID, $opUserID, $flagNumber)
    {
        $message = opUserSolo($opID, $opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 3 = refresh operation info
     */
    public static function broadcastOperationRefresh($opID, $campaignID, $flagNumber)
    {
        $message = NewOperation::where('id', $opID)
            ->with([
                'campaign' => function ($q) use ($campaignID) {
                    $q->where('id', $campaignID);
                },
                'campaign.status',
                'campaign.constellation:id,constellation_name,region_id',
                'campaign.constellation.region:id,region_name',
                'campaign.alliance:id,name,ticker,standing,url,color',
                'campaign.system:id,system_name,adm',
            ])
            ->first();

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = set Read Only
     */
    public static function broadcastOperationSetReadOnly($opID, $flagNumber, $message)
    {
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = update operation user list,
     */
    public static function broadcastOperationUserList($opID, $flagNumber)
    {
        $userIDs = OperationUserList::where('operation_id', $opID)->pluck('user_id');
        $message = userListAll($userIDs, $opID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationAdminUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 3 = Add/Update Own Char info -
     * 5 = RemoveChar -
     */
    public static function broadcastuserOwnSolo($opUserID, $userID, $flagNumber, $opID)
    {
        $message = ownUsersolo($opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'userid' => $opUserID,
            'id' => $userID,
            'message' => $message,
        ]);

        broadcast(new OperationOwnUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = Open Add Char overlay -
     *  @param  int  $type
     * 1 = Normal message -
     * 2 = read only message
     */
    public static function broadcastAllCharOverlay($flagNumber, $opID, $type)
    {
        $users = OperationUserList::where('operation_id', $opID)
            ->withCount(['ownUsers' => function ($query) use ($opID) {
                $query->where('operation_id', $opID);
            }])->get();

        foreach ($users as $user) {
            if ($user->own_users_count == 0) {
                $userID = $user->user_id;

                $flag = collect([
                    'flag' => $flagNumber,
                    'op_id' => $opID,
                    'id' => $userID,
                    'type' => $type,
                ]);

                broadcast(new OperationOwnUpdate($flag));
            }
        }
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = Add Custom Operation To list -
     * 2 = Update Custom Operation On List -
     * 3 = Delete CUstom Operation from list -
     */
    public static function broadcastCustomOperationSolo($opID, $flagNumber)
    {
        $message = customOperationSolo($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new CustomOperationPageUpdate($flag));
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
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
