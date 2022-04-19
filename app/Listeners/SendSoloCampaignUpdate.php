<?php

namespace App\Listeners;

use App\Events\SoloCampaignUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSoloCampaignUpdate
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
     * @param  \App\Events\SoloCampaignUpdate  $event
     * @return void
     */
    public function handle(SoloCampaignUpdate $event)
    {
        //
    }
}
