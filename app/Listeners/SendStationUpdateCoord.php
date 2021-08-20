<?php

namespace App\Listeners;

use App\Events\StationUpdateCoord;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationUpdateCoord
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
     * @param  StationUpdateCoord  $event
     * @return void
     */
    public function handle(StationUpdateCoord $event)
    {
        //
    }
}
