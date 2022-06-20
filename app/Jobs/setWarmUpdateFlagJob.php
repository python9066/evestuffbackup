<?php

namespace App\Jobs;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\Broadcasthelper\Broadcasthelper;
use utils\NewCampaignhelper\NewCampaignhelper;

class setWarmUpdateFlagJob implements ShouldQueue
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
        $campaign =  NewCampaign::where('id', $this->campaign_id)->first();
        $campaign->update(['status_id' => 5]);
        NewCampaignhelper::campaignSolo($this->campaign_id);
        $operationIDs = NewCampaignOperation::where('campaign_id', $this->campaign_id)->get();
        foreach ($operationIDs as $opID) {
            Broadcasthelper::broadcastOperationRefresh($opID->operation_id, 3);
        }
    }

    public function uniqueId()
    {
        return $this->campaign_id;
    }
}
