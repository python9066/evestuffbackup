<?php

namespace App\Listeners;

use App\Events\ChillSheetMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SentChillSheetMessageUpdate
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
     * @param  ChillSheetMessageUpdate  $event
     * @return void
     */
    public function handle(ChillSheetMessageUpdate $event)
    {
        //
    }
}
