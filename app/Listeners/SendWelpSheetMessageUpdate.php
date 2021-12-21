<?php

namespace App\Listeners;

use App\Events\WelpSheetMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelpSheetMessageUpdate
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
     * @param  \App\Events\WelpSheetMessageUpdate  $event
     * @return void
     */
    public function handle(WelpSheetMessageUpdate $event)
    {
        //
    }
}
