<?php

namespace App\Listeners;

use App\Events\ReconTimerUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReconTimerUpdate
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
     * @param  ReconTimerUpdate  $event
     * @return void
     */
    public function handle(ReconTimerUpdate $event)
    {
        //
    }
}
