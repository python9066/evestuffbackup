<?php

namespace App\Listeners;

use App\Events\CustomOperationPageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCustomOperationPageUpdate
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
     * @param  \App\Events\CustomOperationPageUpdate  $event
     * @return void
     */
    public function handle(CustomOperationPageUpdate $event)
    {
        //
    }
}
