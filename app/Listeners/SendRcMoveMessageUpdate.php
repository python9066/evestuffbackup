<?php

namespace App\Listeners;

use App\Events\RcMoveMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcMoveMessageUpdate
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
     * @param  RcMoveMessageUpdate  $event
     * @return void
     */
    public function handle(RcMoveMessageUpdate $event)
    {
        //
    }
}
