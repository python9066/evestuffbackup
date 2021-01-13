<?php

namespace utils\Loghelper;

use App\Events\CampaignSystemUpdate;
use App\Models\Logging;

use function GuzzleHttp\json_decode;

class Loghelper
{

    public static function logadd($logging)
    {
        Logging::create($logging->all());
        $flag = collect([
            'flag' => 10,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }
}
