<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use utils\Helper\Helper;

class UpdateEveUserCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eveusercount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the eve usercount';

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
        Helper::checkeve();
    }
}
