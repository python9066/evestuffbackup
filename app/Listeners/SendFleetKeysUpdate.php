<?php

namespace App\Listeners;

use App\Events\FleetKeysUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFleetKeysUpdate
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
     * @param  FleetKeysUpdate  $event
     * @return void
     */
    public function handle(FleetKeysUpdate $event)
    {
        //
    }
}
