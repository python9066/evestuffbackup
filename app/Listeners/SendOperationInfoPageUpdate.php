<?php

namespace App\Listeners;

use App\Events\OperationInfoPageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOperationInfoPageUpdate
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
     * @param  \App\Events\OperationInfoPageUpdate  $event
     * @return void
     */
    public function handle(OperationInfoPageUpdate $event)
    {
        //
    }
}
