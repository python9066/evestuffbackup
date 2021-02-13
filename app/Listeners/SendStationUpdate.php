<?php

namespace App\Listeners;

use App\Events\StationUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationUpdate
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
     * @param  StationUpdate  $event
     * @return void
     */
    public function handle(StationUpdate $event)
    {
        //
    }
}
