<?php

namespace App\Jobs;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\OperationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class setDeleteFlagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $campaign_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaign = NewCampaign::where('id', $this->campaign_id)->with('operations')->first();
        $operations = $campaign->operations;
        foreach ($operations as $operation) {
            $count = $operation->campaign->count();
            if ($count == 1) {
                $this->deleteOperation($operation);
            }

        }
        NewCampaignOperation::where('campaign_id', $this->campaign_id)->delete();

    }

    public function deleteOperation($operation)
    {
        $operationUsers = OperationUser::where('operation_id', $operation->id)->get();
        foreach ($operationUsers as $operationUser) {
            $operationUser->opertaion_id = null;
            $operationUser->save();
        }
        $operation->delete();
    }

}
