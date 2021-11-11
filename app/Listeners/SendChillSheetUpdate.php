<?php

namespace App\Listeners;

use App\Events\ChillSheetUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChillSheetUpdate
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
     * @param  ChillSheetUpdate  $event
     * @return void
     */
    public function handle(ChillSheetUpdate $event)
    {
        //
    }
}
