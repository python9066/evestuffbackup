<?php

namespace App\Listeners;

use App\Events\CampaignSolaSystemUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCampaignSolaSystemUpdate
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
     * @param  CampaignSolaSystemUpdate  $event
     * @return void
     */
    public function handle(CampaignSolaSystemUpdate $event)
    {
        //
    }
}
