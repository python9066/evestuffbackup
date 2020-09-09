<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Events\CampaginChanged;
use Illuminate\Http\Request;
use utils\Campaginhelper\Campaginhelper;
use utils\Helper\Helper;

class CampaginController extends Controller
{
    public function getCampagins()
    {
        $status = Helper::checkeve();
        if ($status == 1){
            $flag = Campaginhelper::update();

            if($flag == 1){
                broadcast(new CampaginChanged ($flag))->toOthers();
            }
            return $flag;

        }
    }

    public function index()
    {
        return ['campaigns' => Campaign::all()];
    }







}
