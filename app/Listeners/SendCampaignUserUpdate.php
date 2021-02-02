<?php

namespace App\Listeners;

use App\Events\CampaignUserUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCampaignUserUpdate
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
     * @param  CampaignUserUpdate  $event
     * @return void
     */
    public function handle(CampaignUserUpdate $event)
    {
        //
    }
}
