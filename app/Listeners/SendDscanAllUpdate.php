<?php

namespace App\Listeners;

use App\Events\DscanAllUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDscanAllUpdate
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
    public function handle(DscanAllUpdate $event): void
    {
        //
    }
}
