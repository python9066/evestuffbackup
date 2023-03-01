<?php

namespace App\Listeners;

use App\Events\TowerSheetMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTowerSheetMessageUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TowerSheetMessageUpdate $event): void
    {
        //
    }
}
