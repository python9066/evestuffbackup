<?php

namespace App\Listeners;

use App\Events\AmmoRequestUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAmmoRequestUpdate
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
     * @param  AmmoRequestUpdate  $event
     * @return void
     */
    public function handle(AmmoRequestUpdate $event)
    {
        //
    }
}
