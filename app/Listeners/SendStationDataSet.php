<?php

namespace App\Listeners;

use App\Events\StationDataSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationDataSet
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
     * @param  StationDataSet  $event
     * @return void
     */
    public function handle(StationDataSet $event)
    {
        //
    }
}
