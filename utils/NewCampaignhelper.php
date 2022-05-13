<?php

namespace utils\NewCampaignhelper;

use App\Events\OperationUpdate;
use App\Models\Campaign;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use App\Models\System;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use utils\Broadcasthelper\Broadcasthelper;
use utils\Helper\Helper;

class NewCampaignhelper
{

    public static function newUpdate()
    {


        $deathCampaign = NewCampaign::where('status_id', 10)->get();

        foreach ($deathCampaign as $c) {
            // TODO: Finish everything else that needs to be cleaned up when a campaign is over.
            $campaginOperation = NewCampaignOperation::where('campaign_id', $c->id)->get();
            foreach ($campaginOperation as $co) {

                $op = NewOperation::where('id', $co->operation_id)->first();
                if ($op->solo == 1) {
                    $op->delete();
                }
                $co->delete();
            }

            $c->delete();
        }

        // * Set check flag to 0
        NewCampaign::where('id', '>', 0)->update(['check' => 0]);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            'User-Agent' => 'evestuff.online python9066@gmail.com'
        ])->get("https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility");

        $campaigns = $response->collect();
        foreach ($campaigns as $campaign) {
            $event_type = $campaign['event_type'];
            if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                $score_changed = false;
                if ($event_type == 'ihub_defense') {
                    $event_type = 32458;
                } else {
                    $event_type = 32226;
                }


                $id = $campaign['campaign_id'];
                $old = NewCampaign::where('id', $id)->first();
                if ($old) {
                    // * Checking if the score has changed
                    if ($campaign['attackers_score'] != $old->attackers_score) {
                        $attackers_score_old = $old->attackers_score;
                        $defenders_score_old = $old->defenders_score;
                        $old->update([
                            'attackers_score_old' => $attackers_score_old,
                            'defenders_score_old' => $defenders_score_old
                        ]);
                        $score_changed = true;
                    }
                }

                $time = $campaign['start_time'];
                $start_time = Helper::fixtime($time);
                $data = array();
                $data = array(
                    'attackers_score' => $campaign['attackers_score'],
                    'constellation_id' => $campaign['constellation_id'],
                    'alliance_id' => $campaign['defender_id'],
                    'defenders_score' => $campaign['defender_score'],
                    'event_type' => $event_type,
                    'system_id' => $campaign['solar_system_id'],
                    'start_time' => $start_time,
                    'structure_id' => $campaign['structure_id'],
                    'check' => 1,
                );

                NewCampaign::updateOrCreate(['id' => $id], $data);
                echo $score_changed;
                // * If Score has changed

                if ($score_changed) {
                    echo "I AM IN";
                    $campaign = NewCampaign::where('id', $id)->first();
                    $campaignOperations = NewCampaignOperation::where('campaign_id', $id)->get();
                    $bNode = $campaign->b_node;
                    $rNode = $campaign->r_node;
                    $campaignNodes = NewSystemNode::where('campaign_id', $id)
                        ->where(function ($q) {
                            $q->where('node_status', 4)
                                ->orWhere('node_status', 5);
                        })->get();

                    foreach ($campaignNodes as $campaignNode) {
                        $system_id = $campaignNode->system_id;
                        if ($campaignNode->node_status == 4) {
                            $bNode = $bNode++;
                        } else {
                            $rNode = $rNode++;
                        }

                        $opUserNodes = NewUserNode::where('node_id', $campaignNode->id)->get();
                        if ($opUserNodes) {
                            $opUserNodes->update([
                                'new_user_node_id' => null,
                                'user_status_id' => 3
                            ]);

                            foreach ($opUserNodes as $opUserNode) {
                                $opUser = OperationUser::where('id', $opUserNode->operation_user_id)->first();
                                $user = User::where('id', $opUser->user_id)->first();
                                Broadcasthelper::broadcastuserOwnSolo($opUserNode->operation_user_id, $user->id, 3);
                                foreach ($campaignOperations as $campaignOperation) {

                                    Broadcasthelper::broadcastuserSolo($campaignOperation->operation_id, $opUser->id, 6);
                                }
                            }
                            $opUserNodes->delete();
                        }
                        $campaignNode->delete();
                        Broadcasthelper::broadcastsystemSolo($system_id, 7);
                    }

                    $campaign->update(['b_node' => $bNode, 'r_node' => $rNode]);
                    foreach ($campaignOperations as $campaignOperation) {
                        Broadcasthelper::broadcastCampaignSolo($campaign->id, $campaignOperation->operation_id, 4);
                    }
                }

                // * Setting everything up for a new campaign
                if (NewCampaignOperation::where('campaign_id', $id)->count() == 0) {
                    $uuid = Str::uuid();
                    $system = System::where('id', $campaign['solar_system_id'])->first();
                    $systemName = $system->system_name;
                    if ($event_type == 32458) {
                        $type = "Ihub";
                    } else {
                        $type = "TCU";
                    }
                    $title = $systemName . " - " . $type;
                    $newOp = NewOperation::create([
                        'link' => $uuid,
                        'solo' => 1,
                        'status' => 1,
                        'title' => $title,
                    ]);

                    NewCampaignOperation::create([
                        'campaign_id' => $id,
                        'operation_id' => $newOp->id
                    ]);

                    $campaignSystemsIDs = System::where('constellation_id', $campaign['constellation_id'])->pluck('id');
                    foreach ($campaignSystemsIDs as $systemid) {
                        NewCampaignSystem::create([
                            'system_id' => $systemid,
                            'new_campaign_id' => $id
                        ]);
                    }
                }
            }
        }



        $noCampaigns = NewOperation::where('status', '!=', 0)->doesntHave('campaign')->get();
        foreach ($noCampaigns as $noCampaign) {
            NewCampaignOperation::where('operation_id', $noCampaign->id)->delete();
            $noCampaign->delete();
        }

        // * Change new upcoming status to warmup (done an hour before start time)
        $warmupCampaigns = NewCampaign::where('start_time', '>', now())
            ->where('start_time', '=<', now()->addHour())
            ->where('status_id', 1)
            ->where('check', 1)
            ->get();
        foreach ($warmupCampaigns as $start) {
            $start->update(['status_id' => 5, 'check' => 1]);
        };

        // * Checks to see if a campaign has moved from warmup to active
        $startedCampaigns = NewCampaign::where('start_time', '<=', now())
            ->where('status_id', 5)
            ->where('check', 1)
            ->get();
        foreach ($startedCampaigns as $start) {
            $start->update(['status_id' => 2, 'check' => 1]);
        };

        //! IF CHECK = 0, that means its not on the API which means the campaing is over.
        // * Set Campaign to finished(3) but able to access still for 10mins
        NewCampaign::where('check', 0)
            ->whereNull('end_time')
            ->update([
                'end_time' => now(),
                'status_id' => 3,
                'check' => 1,
            ]);

        // * Check if the campaign have been over more than 10mins, if true set it to finsiehd(3)
        NewCampaign::where('check', 0)
            ->where('status_id', 2)
            ->where('end_time', '>', now()->subMinutes(10))
            ->update([
                'status_id' => 3,
                'check' => 1
            ]);


        // * If campaign have been over for more than 10mins set it to finished(4), to show on the finished tab for 24 hours
        NewCampaign::where('check', 0)
            ->where('status_id', 3)
            ->where('end_time', '<', now()->subMinutes(10))->update([
                'status_id' => 4,
                'check' => 1
            ]);

        // * If campaign has been over for more than 24 hours.  Delete the campaign.
        NewCampaign::where('check', 0)
            ->where('status_id', 4)
            ->where('end_time', '<', now()->subDay())->update([
                'status_id' => 10,
                'check' => 1
            ]);
    }


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
                "userNode.node"
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
                "userNode.node"
            ])
            ->first();
    }

    public static function systemsAll($contellationIDs)
    {
        return System::whereIn('constellation_id', $contellationIDs)
            ->with([
                'newCampaigns',
                'newNodes.nodeStatus',
                'newNodes.nonePrimeNodeUser.opUser.user',
                'newNodes.nonePrimeNodeUser.nodeStatus',
                'newNodes.primeNodeUser.opUser.user',
                'newNodes.primeNodeUser.nodeStatus',
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
                'newNodes.nonePrimeNodeUser.opUser.user',
                'newNodes.nonePrimeNodeUser.nodeStatus',
                'newNodes.primeNodeUser.opUser.user',
                'newNodes.primeNodeUser.nodeStatus',
                'newNodes.system'
            ])
            ->first();
    }


    public static function campaignSolo($campaignID)
    {
        return NewCampaign::where('id', 97050)->with([
            'status',
            'constellation:id,constellation_name,region_id',
            'constellation.region:id,region_name',
            'alliance:id,name,ticker,standing,url,color',
            'system:id,system_name,adm',
        ])->first();
    }
}
