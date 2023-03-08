<?php

namespace App\Listeners;

use App\Events\StationWatchListSettingPageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStationWatchListSettingPageUpdate
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
    public function handle(StationWatchListSettingPageUpdate $event): void
    {
        //
    }
}
