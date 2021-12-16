<?php

namespace App\Listeners;

use App\Events\RcSheetAddLogging;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcSheetAddLogging
{
    /**
     * Create the ev  ent listener.
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
     * @param  RcSheetAddLogging  $event
     * @return void
     */
    public function handle(RcSheetAddLogging $event)
    {
        //
    }
}
