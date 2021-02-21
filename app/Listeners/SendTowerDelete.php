<?php

namespace App\Listeners;

use App\Events\TowerDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTowerDelete
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
     * @param  TowerDelete  $event
     * @return void
     */
    public function handle(TowerDelete $event)
    {
        //
    }
}
