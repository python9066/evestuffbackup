<?php

namespace App\Console\Commands;

use App\Models\Station;
use Illuminate\Console\Command;
use utils\Helper\Helper;
use utils\Notificationhelper\Notifications;
use utils\Timerhelper\Timerhelper;

class UpdateReconStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reconstations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will pull all station data from recon';

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
        Notifications::reconUpdate();
        $stations = Station::where('id', '>=', 1000000000)->count();
        $this->info('found ' . $stations . ' stations');
    }
}
