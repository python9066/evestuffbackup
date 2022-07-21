<?php

use App\Jobs\setJustOverFlagJob;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\OperationUser;
use App\Models\System;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

if (!function_exists('newUpdateCampaigns')) {
    function newUpdateCampaigns()
    {
        activity()->disableLogging();
        $updatedCampaignID = collect();

        // * Set check flag to 0
        $n = NewCampaign::where('id', '>', 0)->get();
        foreach ($n as $n) {
            $n->update(['check' => 0]);
        }
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online python9066@gmail.com',
        ])->get('https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility');
        $res = $response->collect();
        foreach ($res as $r) {
            $event_type = $r['event_type'];
            if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                $score_changed = false;
                if ($event_type == 'ihub_defense') {
                    $event_type = 32458;
                    $event_type_name = 'Ihub';
                } else {
                    $event_type = 32226;
                    $event_type_name = 'TCU';
                }

                $id = $r['campaign_id'];
                $old = NewCampaign::where('id', $id)->first();
                if ($old) {
                    // * Checking if the score has changed
                    if ($r['attackers_score'] != $old->attackers_score) {
                        $old->attackers_score_old = $old->attackers_score;
                        $old->defenders_score_old = $old->defenders_score;
                        $old->save();
                        $score_changed = true;
                    }
                }
                $systemN = System::where('id', $r['solar_system_id'])->first();
                $systemNamee = $systemN->system_name;
                $cName = $systemNamee . ' - ' . $event_type_name;
                $time = $r['start_time'];
                $start_time = fixtime($time);
                $data = [];
                $data = [
                    'attackers_score' => $r['attackers_score'],
                    'constellation_id' => $r['constellation_id'],
                    'alliance_id' => $r['defender_id'],
                    'defenders_score' => $r['defender_score'],
                    'event_type' => $event_type,
                    'system_id' => $r['solar_system_id'],
                    'start_time' => $start_time,
                    'structure_id' => $r['structure_id'],
                    'check' => 1,
                    'name' => $cName,
                ];

                $checkNew = NewCampaign::where('id', $id)->count();
                if ($checkNew == 0) {
                    $updatedCampaignID->push($id);
                }
                NewCampaign::updateOrCreate(['id' => $id], $data);
                echo $score_changed;
                // * If Score has changed

                if ($score_changed) {
                    // $updatedCampaignID->push($id);

                    echo ' -  I AM IN   -';
                    $campaign = NewCampaign::where('id', $id)->first();
                    $campaignOperations = NewCampaignOperation::where('campaign_id', $id)->get();
                    $bNode = $campaign->b_node;
                    $rNode = $campaign->r_node;
                    echo $id;
                    $campaignNodes = NewSystemNode::where('campaign_id', $id)->whereIn('node_status', [4, 5])->get();
                    foreach ($campaignNodes as $campaignNode) {
                        $system_id = $campaignNode->system_id;
                        if ($campaignNode->node_status == 4) {
                            $bNode = $bNode + 1;
                            echo 'yay add 1 to blue';
                        } else {
                            $rNode = $rNode + 1;
                            echo 'yay add 1 to red';
                        }
                        $campaignNode->delete();
                        broadcastsystemSolo($system_id, 7);
                    }

                    $campaign->update(['b_node' => $bNode, 'r_node' => $rNode]);
                    foreach ($campaignOperations as $campaignOperation) {
                        broadcastCampaignSolo($campaign->id, $campaignOperation->operation_id, 4);
                    }
                }

                // * Setting everything up for a new campaign
                if (NewCampaignOperation::where('campaign_id', $id)->count() == 0) {
                    $uuid = Str::uuid();
                    $system = System::where('id', $r['solar_system_id'])->first();
                    $systemName = $system->system_name;
                    if ($event_type == 32458) {
                        $type = 'Ihub';
                    } else {
                        $type = 'TCU';
                    }
                    $title = $systemName . ' - ' . $type;
                    $newOp = NewOperation::create([
                        'link' => $uuid,
                        'solo' => 1,
                        'status' => 1,
                        'title' => $title,
                    ]);

                    NewCampaignOperation::create([
                        'campaign_id' => $id,
                        'operation_id' => $newOp->id,
                    ]);

                    $campaignSystemsIDs = System::where('constellation_id', $r['constellation_id'])->pluck('id');
                    foreach ($campaignSystemsIDs as $systemid) {
                        NewCampaignSystem::create([
                            'system_id' => $systemid,
                            'new_campaign_id' => $id,
                        ]);
                    }
                }
            }
        }
        $yesterday = now()->subDay();
        $oldCampaigns = NewCampaign::where('status_id', 4)->where('end_time', '<', $yesterday)->get();
        foreach ($oldCampaigns as $oldCampaign) {
            $campaign = NewCampaign::where('id', $oldCampaign->id)->with('operations')->first();
            $operations = $campaign->operations;
            foreach ($operations as $operation) {
                $count = $operation->campaign->count();
                if ($count == 1) {
                    $operationUsers = OperationUser::where('operation_id', $operation->id)->get();
                    foreach ($operationUsers as $operationUser) {
                        $operationUser->operation_id = null;
                        $operationUser->save();
                    }
                    $operation->delete();
                }
            }

            NewCampaignOperation::where('campaign_id', $oldCampaign->id)->delete();
        }

        $noCampaigns = NewOperation::doesntHave('campaign')->get();
        foreach ($noCampaigns as $noCampaign) {
            $n = NewCampaignOperation::where('operation_id', $noCampaign->id)->get();
            foreach ($n as $n) {
                $n->delete();
            }
        }
        $ns = NewCampaign::where('check', 0)
            ->whereNull('end_time')->get();

        foreach ($ns as $n) {
            if ($n->start_time < now()->subMinutes(30))
                setJustOverFlagJob::dispatch($n->id, $n->defenders_score)->onQueue('campaigns');
        }
        // * Check if the campaign have been over more than 10mins, if true set it to finsiehd(3)

        $updatedCampaignIDs = $updatedCampaignID->unique();
        print_r($updatedCampaignIDs);
        foreach ($updatedCampaignIDs as $updatedCampaignID) {
            $ops = NewCampaignOperation::where('campaign_id', $updatedCampaignID)->get();
            foreach ($ops as $op) {
                broadcastCampaignSolo($updatedCampaignID, $op->operation_id, 3);

                $operation = NewOperation::where('id', $op->operation_id)->where('solo', 1)->first();
                if ($operation) {
                    broadcastSoloOpSoloOp(1, $operation->id);
                }
            }
        }

        activity()->enableLogging();
    }
}

if (!function_exists('ownUserAll')) {
    function ownUserAll()
    {
        return OperationUser::where('user_id', Auth::id())
            ->with(['userrole'])
            ->get();
    }
}
if (!function_exists('ownUsersolo')) {
    function ownUsersolo($id)
    {
        return OperationUser::where('id', $id)
            ->with(['userrole'])
            ->first();
    }
}
if (!function_exists('opUserAll')) {
    function opUserAll($opID)
    {
        return OperationUser::where('operation_id', $opID)
            ->with([
                'user:id,name',
                'userrole',
                'userstatus',
                'system',
                'userNode.node',
            ])
            ->get();
    }
}
if (!function_exists('opUserSolo')) {
    function opUserSolo($opID, $id)
    {
        return OperationUser::where('operation_id', $opID)
            ->where('id', $id)
            ->with([
                'user:id,name',
                'userrole',
                'userstatus',
                'system',
                'userNode.node',
            ])
            ->first();
    }
}
if (!function_exists('systemsAll')) {
    function systemsAll($contellationIDs, $opID)
    {
        $campaignIDs = NewCampaignOperation::where('operation_id', $opID)->pluck('campaign_id');
        return System::whereIn('constellation_id', $contellationIDs)
            ->with([
                'newCampaigns.operations',
                'newNodes' => function ($n) use ($campaignIDs) {
                    $n->whereIn('campaign_id', $campaignIDs);
                },
                'newNodes.nodeStatus',
                'newNodes.campaign',
                'newNodes.nonePrimeNodeUser.opUser.user',
                'newNodes.nonePrimeNodeUser.nodeStatus',
                'newNodes.primeNodeUser.opUser.user',
                'newNodes.primeNodeUser.nodeStatus',
                'newNodes.system',
                'scoutUser',
                'checkUser',
            ])
            ->get();
    }
}
if (!function_exists('systemSolo')) {
    function systemSolo($systemID, $opID)
    {
        $campaignIDs = NewCampaignOperation::where('operation_id', $opID)->pluck('campaign_id');
        return System::where('id', $systemID)
            ->with([
                'newCampaigns.operations',
                'newNodes' => function ($n) use ($campaignIDs) {
                    $n->whereIn('campaign_id', $campaignIDs);
                },
                'newNodes.nodeStatus',
                'newNodes.campaign',
                'newNodes.nonePrimeNodeUser.opUser.user',
                'newNodes.nonePrimeNodeUser.nodeStatus',
                'newNodes.primeNodeUser.opUser.user',
                'newNodes.primeNodeUser.nodeStatus',
                'newNodes.system',
                'scoutUser',
                'checkUser',
            ])
            ->first();
    }
}
if (!function_exists('campaignSolo')) {
    function campaignSolo($campaignID)
    {
        return NewCampaign::where('id', $campaignID)->with([
            'status',
            'constellation:id,constellation_name,region_id',
            'constellation.region:id,region_name',
            'alliance:id,name,ticker,standing,url,color',
            'system:id,system_name,adm',
        ])->first();
    }
}
if (!function_exists('customOperationSolo')) {
    function customOperationSolo($opID)
    {
        return NewOperation::where('id', $opID)
            ->with(['campaign.system', 'campaign.alliance'])
            ->first();
    }
}
if (!function_exists('userListAll')) {
    function userListAll($userIDs, $opID)
    {
        return User::whereIn('id', $userIDs)
            ->with(['opUsers' => function ($t) use ($opID) {
                $t->where('operation_id', $opID);
            }, 'opUsers.userrole'])->select('id', 'name')->get();
    }
}
