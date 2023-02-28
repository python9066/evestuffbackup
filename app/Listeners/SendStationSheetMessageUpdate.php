<?php

namespace App\Listeners;

use App\Events\StationSheetMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationSheetMessageUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StationSheetMessageUpdate $event): void
    {
        //
    }
}
