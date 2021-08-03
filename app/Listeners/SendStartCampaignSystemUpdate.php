<?php

namespace App\Listeners;

use App\Events\StartCampaignSystemUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStartCampaignSystemUpdate
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
     * @param  StartCampaignSystemUpdate  $event
     * @return void
     */
    public function handle(StartCampaignSystemUpdate $event)
    {
        //
    }
}
