<?php

namespace App\Listeners;

use App\Events\NodeMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNodeMessageUpdate
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
     * @param  NodeMessageUpdate  $event
     * @return void
     */
    public function handle(NodeMessageUpdate $event)
    {
        //
    }
}
