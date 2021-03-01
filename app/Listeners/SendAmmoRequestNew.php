<?php

namespace App\Listeners;

use App\Events\AmmoRequestNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAmmoRequestNew
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
     * @param  AmmoRequestNew  $event
     * @return void
     */
    public function handle(AmmoRequestNew $event)
    {
        //
    }
}
