<?php

namespace App\Listeners;

use App\Events\TowerMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTowerMessageUpdate
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
     * @param  TowerMessageUpdate  $event
     * @return void
     */
    public function handle(TowerMessageUpdate $event)
    {
        //
    }
}
