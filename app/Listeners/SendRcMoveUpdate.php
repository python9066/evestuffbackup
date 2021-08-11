<?php

namespace App\Listeners;

use App\Events\RcMoveUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcMoveUpdate
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
     * @param  RcMoveUpdate  $event
     * @return void
     */
    public function handle(RcMoveUpdate $event)
    {
        //
    }
}
