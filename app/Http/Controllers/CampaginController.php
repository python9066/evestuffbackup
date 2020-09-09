<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use utils\Campaginhelper\Campaginhelper;
use utils\Helper\Helper;

class CampaginController extends Controller
{
    public function updateCampagins()
    {
        $status = Helper::checkeve();
        if ($status == 1){
            $flag = Campaginhelper::update();
            return $flag;

        }
    }
}
