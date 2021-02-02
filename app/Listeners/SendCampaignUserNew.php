<?php

namespace App\Listeners;

use App\Events\CampaignUserNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCampaignUserNew
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
     * @param  CampaignUserNew  $event
     * @return void
     */
    public function handle(CampaignUserNew $event)
    {
        //
    }
}
