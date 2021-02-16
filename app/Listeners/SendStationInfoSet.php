<?php

namespace App\Listeners;

use App\Events\StationInfoSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationInfoSet
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
     * @param  StationInfoSet  $event
     * @return void
     */
    public function handle(StationInfoSet $event)
    {
        //
    }
}
