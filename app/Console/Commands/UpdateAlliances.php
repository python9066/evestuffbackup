<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Alliancehelper\Alliancehelper;
use utils\Helper\Helper;

class UpdateAlliances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:alliances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update alliances and standing';

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
        $status = Helper::checkeve();
        if ($status == 1){
        Alliancehelper::updateAlliances();
        }
    }
}
