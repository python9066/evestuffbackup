<?php

namespace App\Listeners;

use App\Events\StationCoreUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationCoreUpdate
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
     * @param  StationCoreUpdate  $event
     * @return void
     */
    public function handle(StationCoreUpdate $event)
    {
        //
    }
}
