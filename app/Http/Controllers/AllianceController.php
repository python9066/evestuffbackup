<?php

namespace App\Http\Controllers;

use App\Models\Alliance;
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
        if ($status == 1) {
            Alliancehelper::updateAlliances();
        }
    }

    public function allianceTickList()

    {
        $tickerlist = [];
        $tickers = Alliance::all();
        foreach ($tickers as $ticker) {
            $data = [];
            $data = [
                'text' => $ticker->ticker,
                'value' => $ticker->id
            ];

            array_push($tickerlist, $data);
        }
        return ['allianceticklist' => $tickerlist];
    }
}
