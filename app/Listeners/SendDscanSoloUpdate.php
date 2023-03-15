<?php

namespace App\Listeners;

use App\Events\DscanSoloUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDscanSoloUpdate
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
    public function handle(DscanSoloUpdate $event): void
    {
        //
    }
}
