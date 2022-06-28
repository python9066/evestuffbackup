<?php

namespace App\Console\Commands;

use App\Jobs\AddMainEveIDToUsers;
use App\Models\EveEsiStatus;
use App\Models\User;
use Illuminate\Console\Command;

class AddEveIDToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eveiduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the main eve user id to each user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $status = EveEsiStatus::where('route', '/universe/ids/')->first();
        if ($status->status == "green") {
            $users = User::where('eve_user_id', 0)->get();
            foreach ($users as $user) {
                AddMainEveIDToUsers::dispatch($user->name);
            }
        }
    }
}
