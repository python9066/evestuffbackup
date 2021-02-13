<?php

namespace App\Listeners;

use App\Events\StationNotificationNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationNotificationNew
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
     * @param  StationNotificationNew  $event
     * @return void
     */
    public function handle(StationNotificationNew $event)
    {
        //
    }
}
