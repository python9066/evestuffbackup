<?php

namespace App\Http\Controllers;


use App\Events\CampaignChanged;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CampaignRecords;
use App\Models\CampaignUser;
use Illuminate\Http\Request;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;
use App\Events\CampaignSystemUpdate;

class CampaignController extends Controller
{
    // public function getCampaigns()
    // {
    //     $status = Helper::checkeve();
    //     if ($status == 1) {
    //         $flag = Campaignhelper::update();
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();   dsd
    //         }
    //     }
    // }

    public function getCampaigns()
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
            foreach ($check as $check) {
                Campaignhelper::removeNode($check);
                $flag = collect([
                    'flag' => 4,
                    'id' => $check
                ]);
                broadcast(new CampaignSystemUpdate($flag))->toOthers();
                // }
            }
        }
    }

    public function test()
    {
        $check = 83871;
        Campaignhelper::removeNode($check);
    }
}
