<?php

namespace App\Jobs;

use App\Models\WebWay;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateWebwayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $system_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($system_id)
    {
        $this->system_id = $system_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateWebway($this->system_id);
    }

    public function updateWebway($system_id)
    {
        $variables = json_decode(base64_decode(getenv("PLATFORM_VARIABLES")), true);
        /*
        send = [
            startSystem => start system get from env (1dq)
            endStstem => $system_id
        ]
       return =  api code to request too webway repos $response will be:
            [
                link: UUID of the saved route
                jumps: number of jumps from 1dq ( ID got from env file) to target system
                link_p: UUID of the saved route (with permissions)
                jumps_p: number of jumps from 1dq ( ID got from env file) to target system (with permissions)
            ]
        */

        $startSystem = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
        $webwayURL = env('WEBWAY_URL', ($variables && array_key_exists('WEBWAY_URL', $variables)) ? $variables['WEBWAY_URL'] : null);
        $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);

        $data = [
            'startSystem' => $startSystem,
            'endSystem' => $system_id
        ];

        $response = Http::withToken($webwayToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                "Accept" => "application/json"
            ])->post($webwayURL, $data);

        $response->collect();


        WebWay::updateOrCreate(
            [
                'system_id' => $system_id,
                'permissions' => 0
            ],
            [
                'link' => $response->link,
                'jumps' => $response->jumps
            ]
        );

        WebWay::updateOrCreate(
            [
                'system_id' => $system_id,
                'permissions' => 1
            ],
            [
                'link' => $response->link_p,
                'jumps' => $response->jumps_p
            ]
        );
    }
}
