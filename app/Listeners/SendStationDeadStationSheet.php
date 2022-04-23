<?php

namespace App\Listeners;

use App\Events\StationDeadStationSheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationDeadStationSheet
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
     * @param  \App\Events\StationDeadStationSheet  $event
     * @return void
     */
    public function handle(StationDeadStationSheet $event)
    {
        //
    }
}
