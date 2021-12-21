<?php

namespace App\Listeners;

use App\Events\WelpSheetUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelpSheetUpdate
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
     * @param  \App\Events\WelpSheetUpdate  $event
     * @return void
     */
    public function handle(WelpSheetUpdate $event)
    {
        //
    }
}
