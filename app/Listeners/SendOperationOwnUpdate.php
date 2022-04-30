<?php

namespace App\Listeners;

use App\Events\OperationOwnUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOperationOwnUpdate
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
     * @param  \App\Events\OperationOwnUpdate  $event
     * @return void
     */
    public function handle(OperationOwnUpdate $event)
    {
        //
    }
}
