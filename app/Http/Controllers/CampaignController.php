<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Events\CampaignChanged;
use App\Models\CampaignRecords;
use Illuminate\Http\Request;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;

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
        $collection = collect([
            'Apple' =>
                ['name' => 'iPhone 6S', 'brand' => 'Apple'],

        ]);

        echo $collection;
        dd($collection);


    }
}














