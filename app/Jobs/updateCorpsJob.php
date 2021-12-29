<?php

namespace App\Jobs;

use App\Models\Corp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateCorpsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $corpID, $allianceID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($corpID, $allianceID)
    {
        $this->corpID = $corpID;
        $this->allianceID = $allianceID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->startCorpJob($this->corpID, $this->allianceID);
    }

    public function startCorpJob($corpID, $allianceID)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/corporations/" . $corpID . "/?datasource=tranquility");
        $corpInfo = $response->collect();
        Corp::updateOrCreate(
            ['id' => $corpID],
            [
                'alliance_id' => $allianceID,
                'name' => $corpInfo->get('name'),
                'ticker' => $corpInfo->get('ticker'),
                'active' => 1,
                'url' => "https://images.evetech.net/Corporation/" . $corpID . "_64.png",

            ]
        );
    }
}
