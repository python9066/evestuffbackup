<?php

namespace App\Listeners;

use App\Events\StationSheetMessageUpdate;

class SendStationSheetMessageUpdate
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
     * @param  \App\Events\StationSheetMessageUpdate  $event
     * @return void
     */
    public function handle(StationSheetMessageUpdate $event)
    {
        //
    }
}
