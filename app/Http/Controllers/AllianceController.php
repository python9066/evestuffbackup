<?php

namespace App\Http\Controllers;

use utils\Alliancehelper\Alliancehelper;
use utils\Helper\Helper;

//hierhere
class AllianceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function updateAlliances()

    {
        $status = Helper::checkeve();
        if ($status == 1){
        Alliancehelper::updateAlliances();
        }
    }


}
