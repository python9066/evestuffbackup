<?php

namespace App\Listeners;

use App\Events\StationAttackMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationAttackMessageUpdate
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
     * @param  StationAttackMessageUpdate  $event
     * @return void
     */
    public function handle(StationAttackMessageUpdate $event)
    {
        //
    }
}
