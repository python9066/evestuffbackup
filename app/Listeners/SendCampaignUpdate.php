<?php

namespace App\Listeners;

use App\Events\CampaignUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCampaignUpdate
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
     * @param  CampaignUpdate  $event
     * @return void
     */
    public function handle(CampaignUpdate $event)
    {
        //
    }
}
