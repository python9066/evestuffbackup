<?php

namespace App\Listeners;

use App\Events\LoggingUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLoggingUpdate
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
     * @param  LoggingUpdate  $event
     * @return void
     */
    public function handle(LoggingUpdate $event)
    {
        //
    }
}
