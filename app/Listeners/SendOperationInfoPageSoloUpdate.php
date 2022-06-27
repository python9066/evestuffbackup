<?php

namespace App\Listeners;

use App\Events\OperationInfoPageSoloUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOperationInfoPageSoloUpdate
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
     * @param  \App\Events\OperationInfoPageSoloUpdate  $event
     * @return void
     */
    public function handle(OperationInfoPageSoloUpdate $event)
    {
        //
    }
}
