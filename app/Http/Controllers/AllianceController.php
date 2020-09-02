<?php

namespace App\Http\Controllers;

use utils\Alliancehelper\Alliancehelper;



//hierhere
class AllianceController extends Controller
{


    public function updateAlliances()

    {
        Alliancehelper::updateAlliances();
    }


}
