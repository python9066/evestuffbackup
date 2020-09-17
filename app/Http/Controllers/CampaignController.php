<?php

namespace App\Http\Controllers;


use App\Events\CampaignChanged;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CampaignRecords;
use App\Models\CampaignUser;
use Illuminate\Http\Request;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;
use App\Events\CampaiganSystemUpdate;

class CampaignController extends Controller
{
    public function getCampaigns()
    {
        $status = Helper::checkeve();
        if ($status == 1) {
            $flag = Campaignhelper::update();
            if ($flag == 1) {
                broadcast(new CampaignChanged($flag))->toOthers();
            }
        }
    }

    public function test()
    {
        $status = Helper::checkeve();
        if ($status == 1) {
            $request = Campaignhelper::update();
            $flag = $request[0];
            if ($flag == 1) {
                broadcast(new CampaignChanged($flag))->toOthers();
            }
            $flag = null;
            $check = $request[1];
            foreach($check as $check){
                $camp = CampaignUser::where('campaign_id',$check)->count();
                if($camp > 0){
                    $flag = collect([
                        'flag' => 4,
                        'id' => $check
                    ]);
                    broadcast(new CampaiganSystemUpdate($flag))->toOthers();
                }

                echo $camp;
            }
            // echo $flag;

        }


    }
}














