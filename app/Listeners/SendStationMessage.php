<?php

namespace App\Listeners;

use App\Events\StationMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationMessage
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
     * @param  StationMessageUpdate  $event
     * @return void
     */
    public function handle(StationMessageUpdate $event)
    {
        //
    }
}
