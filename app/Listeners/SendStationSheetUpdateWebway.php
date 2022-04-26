<?php

namespace App\Listeners;

use App\Events\StationSheetUpdateWebway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationSheetUpdateWebway
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
     * @param  \App\Events\StationSheetUpdateWebway  $event
     * @return void
     */
    public function handle(StationSheetUpdateWebway $event)
    {
        //
    }
}
