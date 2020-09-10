<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Events\CampaignChanged;
use Illuminate\Http\Request;
use utils\Campaignhelper\Campaignhelper;
use utils\Helper\Helper;

class CampaignController extends Controller
{
    public function getCampaigns()
    {
        $status = Helper::checkeve();
        if ($status == 1){
            $flag = Campaignhelper::update();

            if($flag == 1){
                broadcast(new CampaignChanged ($flag))->toOthers();
            }
            return $flag;

        }
    }

    public function index()
    {
        return ['campaigns' => Campaign::all()];
    }







}
