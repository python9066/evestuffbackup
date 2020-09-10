<?php

namespace App\Listeners;

use App\Events\CampaignChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use utils\Campaignhelper\Campaignhelper;

class SendCampaignChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CampaignChanged $event)
    {
        //
    }
}
