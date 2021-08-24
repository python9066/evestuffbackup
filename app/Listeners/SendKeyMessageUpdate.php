<?php

namespace App\Listeners;

use App\Events\KeyMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendKeyMessageUpdate
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
     * @param  KeyMessageUpdate  $event
     * @return void
     */
    public function handle(KeyMessageUpdate $event)
    {
        //
    }
}
