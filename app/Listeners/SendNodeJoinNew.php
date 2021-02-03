<?php

namespace App\Listeners;

use App\Events\NodeJoinNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNodeJoinNew
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
     * @param  NodeJoinNew  $event
     * @return void
     */
    public function handle(NodeJoinNew $event)
    {
        //
    }
}
