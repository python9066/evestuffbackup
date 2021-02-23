<?php

namespace App\Listeners;

use App\Events\StationAttackMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationAttackMessage
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
     * @param  StationAttackMessage  $event
     * @return void
     */
    public function handle(StationAttackMessage $event)
    {
        //
    }
}
