<?php

namespace App\Listeners;

use App\Events\StationNotificationDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationNotificationDelete
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
     * @param  StationNotificationDelete  $event
     * @return void
     */
    public function handle(StationNotificationDelete $event)
    {
        //
    }
}
