<?php

namespace App\Console\Commands;

use App\Models\Station;
use Illuminate\Console\Command;

class CleanUpCoordShhet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:coordsheet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'removes stations that our on nats health.  But some how ended up on coord sheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Station::where('show_on_coord', 1)->where('show_on_rc', 1)->update(['show_on_coord' => 0]);
        Station::where('show_on_coord', 1)->where('show_on_rc_move', 1)->update(['show_on_coord' => 0]);
    }
}
