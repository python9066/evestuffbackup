<?php

namespace App\Listeners;

use App\Events\NodeAttackMessageUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNodeAttackMessageUpdate
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
     * @param  NodeAttackMessageUpdate  $event
     * @return void
     */
    public function handle(NodeAttackMessageUpdate $event)
    {
        //
    }
}
