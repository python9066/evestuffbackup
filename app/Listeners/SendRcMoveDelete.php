<?php

namespace App\Listeners;

use App\Events\RcMoveDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRcMoveDelete
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
     * @param  RcMoveDelete  $event
     * @return void
     */
    public function handle(RcMoveDelete $event)
    {
        //
    }
}
