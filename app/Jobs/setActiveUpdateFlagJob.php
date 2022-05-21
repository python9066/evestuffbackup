<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NewCampaign;
use utils\NewCampaignhelper\NewCampaignhelper;

class setActiveUpdateFlagJob implements ShouldQueue
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
       $campaign =  NewCampaign::where('id',$this->campaign_id)->first();
       $campaign->update(['status_id' => 2]);
       NewCampaignhelper::campaignSolo($this->campaign_id);

    }
}