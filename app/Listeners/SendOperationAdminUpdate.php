<?php

namespace App\Listeners;

use App\Events\OperationAdminUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOperationAdminUpdate
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
     * @param  \App\Events\OperationAdminUpdate  $event
     * @return void
     */
    public function handle(OperationAdminUpdate $event)
    {
        //
    }
}
