<?php

namespace App\Listeners;

use App\Events\RcSheetMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcSheetMessageUpdate
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
     * @param  RcSheetMessageUpdate  $event
     * @return void
     */
    public function handle(RcSheetMessageUpdate $event)
    {
        //
    }
}
