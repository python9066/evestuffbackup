<?php

namespace App\Listeners;

use App\Events\EveUserUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEveUserUpdate
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
     * @param  EveUserUpdate  $event
     * @return void
     */
    public function handle(EveUserUpdate $event)
    {
        //
    }
}
