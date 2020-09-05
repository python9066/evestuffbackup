<?php

namespace App\Http\Controllers;

use utils\Alliancehelper\Alliancehelper;



//hierhere
class AllianceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function updateAlliances()

    {
        Alliancehelper::updateAlliances();
    }


}
