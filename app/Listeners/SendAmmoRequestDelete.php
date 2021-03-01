<?php

namespace App\Listeners;

use App\Events\AmmoRequestDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAmmoRequestDelete
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
     * @param  AmmoRequestDelete  $event
     * @return void
     */
    public function handle(AmmoRequestDelete $event)
    {
        //
    }
}
