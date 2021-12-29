<?php

namespace App\Jobs;

use App\Models\Alliance;
use App\Models\Corp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateAlliancesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $allianceID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allianceID)
    {
        $this->allianceID = $allianceID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->startAlliance($this->allianceID);
    }

    public function startAlliance($allianceID)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/?datasource=tranquility");
        $allianceInfo = $response->collect();

        Alliance::updateOrCreate(
            ['id' => $allianceID],
            [
                'name' => $allianceInfo->get('name'),
                'ticker' => $allianceInfo->get('ticker'),
                'standing' => 0,
                'active' => 1,
                'url' => "https://images.evetech.net/Alliance/" . $allianceID . "_64.png",
                'color' => 0
            ]
        );


        Corp::where('alliance_id', $allianceID)->update(['alliance_id' => null]);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/corporations/?datasource=tranquility");
        $corpIDs = $response->collect();
        // Corp::whereIn('id', $corpIDs)->update(['alliance_id' => $allianceID]);
        foreach ($corpIDs as $corpID) {
            updateCorpsJob::dispatch($corpID, $allianceID)->onQueue('corp');
        }
    }
}
