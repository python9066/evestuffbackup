<?php

namespace App\Listeners;

use App\Events\StationSheetUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationSheetUpdate
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
     * @param  \App\Events\StationSheetUpdate  $event
     * @return void
     */
    public function handle(StationSheetUpdate $event)
    {
        //
    }
}
