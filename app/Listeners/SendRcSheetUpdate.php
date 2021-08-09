<?php

namespace App\Listeners;

use App\Events\RcSheetUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcSheetUpdate
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
     * @param  RcSheetUpdate  $event
     * @return void
     */
    public function handle(RcSheetUpdate $event)
    {
        //
    }
}
