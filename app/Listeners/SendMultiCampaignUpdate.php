<?php

namespace App\Listeners;

use App\Events\MultiCampaignUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMultiCampaignUpdate
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
     * @param  MultiCampaignUpdate  $event
     * @return void
     */
    public function handle(MultiCampaignUpdate $event)
    {
        //
    }
}
