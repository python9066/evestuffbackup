<?php

namespace App\Listeners;

use App\Events\StationNotificationUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationNotificationUpdate
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
     * @param  StationNotificationUpdate  $event
     * @return void
     */
    public function handle(StationNotificationUpdate $event)
    {
        //
    }
}
