<?php

namespace App\Console\Commands;

use App\Models\OperationInfoUserList;
use Illuminate\Console\Command;
use Pusher\Pusher;

class updateOperationInfoUserList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:opinfouserlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all the current users on operation info pages';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        OperationInfoUserList::whereNotNull('id')->update(['delete' => 1]);
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $pusher = new Pusher(
            env('PUSHER_APP_KEY', ($variables && array_key_exists('PUSHER_APP_KEY', $variables)) ? $variables['PUSHER_APP_KEY'] : 'null'),
            env('PUSHER_APP_SECRET', ($variables && array_key_exists('PUSHER_APP_SECRET', $variables)) ? $variables['PUSHER_APP_SECRET'] : 'null'),
            env('PUSHER_APP_ID', ($variables && array_key_exists('PUSHER_APP_ID', $variables)) ? $variables['PUSHER_APP_ID'] : 'null'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER', ($variables && array_key_exists('PUSHER_APP_CLUSTER', $variables)) ? $variables['PUSHER_APP_CLUSTER'] : 'null'),
                'encrypted' => true,
                'useTLS' => true,
                'host' => 'https://newsocket.ledom.uk',
                'port' => 443,
                'scheme' => 'https',
            ]
        );

        $response = $pusher->get('/channels');
        $response = json_decode(json_encode($response), true);
        $channels = $response['channels'];
        $channels = array_keys($channels);
        $data = collect([]);

        foreach ($channels as $channel) {
            $part = explode('.', $channel);
            if ($part[0] === 'private-operationinfooppageown') {
                $keys = collect(['userID', 'opID']);
                $info = explode('-', $part[1]);
                $data1 = collect($info);
                $data1 = $keys->combine($data1);
                $data->push($data1);
            }
        }
        $groups = $data->groupBy('opID');
        // return $groups;
        $this->info($groups);
        foreach ($groups as $group) {
            $opID = (int) $group[0]['opID'];
            $this->info($opID);
            foreach ($group as $op) {
                $userID = (int) $op['userID'];
                $check = OperationInfoUserList::where('operation_info_id', $opID)->where('user_id', $userID)->first();
                if (!$check) {
                    $newOp = new OperationInfoUserList();
                    $newOp->operation_info_id = $opID;
                    $newOp->user_id = $userID;
                    $newOp->delete = 2;
                    $newOp->save();
                } else {
                    $check->delete = 0;
                    $check->save();
                }
            }
        }

        $deleteCheck = OperationInfoUserList::where('delete', 1)->groupBy('operation_info_id')->pluck('operation_info_id');
        $addCheck = OperationInfoUserList::where('delete', 2)->groupBy('operation_info_id')->pluck('operation_info_id');
        $combined = $deleteCheck->merge($addCheck);
        $combined = $combined->unique();
        OperationInfoUserList::where('delete', 1)->delete();
        OperationInfoUserList::where('delete', 2)->update(['delete' => 0]);
        foreach ($combined as $op) {
            $this->info($op);
            broadcastOperationInfoUserList($op, 18);
        }
    }
}
