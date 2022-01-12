<?php

namespace App\Console\Commands;

use App\Jobs\updateAlliancesJob;
use App\Jobs\UpdateStandingJob;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Auth;
use App\Models\Userlogging;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use utils\Alliancehelper\Alliancehelper;
use utils\Helper\Helper;
use App\Models\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;

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

        $now = now();
        // $status = Helper::checkeve();
        // if ($status == 1) {
        // Alliancehelper::updateAlliances();
        // }
        $response =  Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/?datasource=tranquility");
        $allianceIDs = $response->collect();
        $deads =   Alliance::whereNotIn('id', $allianceIDs)->get();
        foreach ($deads as $dead) {
            Corp::where('alliance_id', $dead->id)->update(['alliance_id' => null]);
            $dead->delete();
        }

        foreach ($allianceIDs as $allianceID) {
            // updateAlliancesJob::dispatch($allianceID)->onQueue('alliance');
            $this->startAlliance($allianceID);
        }

        $retrys = Alliance::where('updated_at', '<', $now)->get();
        foreach ($retrys as $retry) {
            $this->startAlliance($retry->id);
        }

        $retrys = Corp::where('active', 5)->get();
        foreach ($retrys as $retry) {
            $this->startCorpJob($retry->id, $retry->alliance_id);
        }

        // UpdateStandingJob::dispatch()->onQueue('allince')->delay(now()->addMinutes(20));
        $this->runStandingUpdate();
    }

    public function startAlliance($allianceID)
    {



        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/?datasource=tranquility");

        if ($response->successful()) {
            $allianceInfo = $response->collect();
            Alliance::updatedOrCreate(
                ['id' => $allianceInfo->id],
                [
                    'name' => $allianceInfo->get('name'),
                    'ticker' => $allianceInfo->get('ticker'),
                    'standing' => 0,
                    'active' => 1,
                    'url' => "https://images.evetech.net/Alliance/" . $allianceID . "_64.png",
                    'color' => 0
                ]
            );
            Alliance::where('id', $allianceInfo->id)->touch();
            Corp::where('alliance_id', $allianceID)->update(['alliance_id' => null]);
            $responseCorp = Http::withHeaders([
                'Content-Type' => 'application/json',
                "Accept" => "application/json"
            ])->get("https://esi.evetech.net/latest/alliances/" . $allianceID . "/corporations/?datasource=tranquility");
            if ($responseCorp->successful()) {
                $corpIDs = $responseCorp->collect();
                foreach ($corpIDs as $corpID) {
                    $this->startCorpJob($corpID, $allianceID);
                }
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Remain'][0];
                sleep($sleep);
            }
        } else {
            $headers = $response->headers();
            $sleep = $headers['X-Esi-Error-Limit-Remain'][0];
            sleep($sleep);
        }
    }

    public function startCorpJob($corpID, $allianceID)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get("https://esi.evetech.net/latest/corporations/" . $corpID . "/?datasource=tranquility");
        if ($response->successful()) {
            $corpInfo = $response->collect();
            Corp::updateOrCreate(
                ['id' => $corpID],
                [
                    'alliance_id' => $allianceID,
                    'name' => $corpInfo->get('name'),
                    'ticker' => $corpInfo->get('ticker'),
                    'color' => 0,
                    'standing' => 0,
                    'active' => 1,
                    'url' => "https://images.evetech.net/Corporation/" . $corpID . "_64.png",

                ]
            );
        } else {
            Corp::updateOrCreate(
                ['id' => $corpID],
                [
                    'alliance_id' => $allianceID,
                    'name' => null,
                    'ticker' => null,
                    'color' => 0,
                    'standing' => 0,
                    'active' => 5,
                    'url' => "https://images.evetech.net/Corporation/" . $corpID . "_64.png",

                ]
            );
            $headers = $response->headers();
            $sleep = $headers['X-Esi-Error-Limit-Remain'][0];
            sleep($sleep);
        }
    }

    public function runStandingUpdate()
    {
        $this->checkKeys();
        $this->updateStanding();
    }

    public function checkKeys()
    {
        $auths = Auth::all();
        foreach ($auths as $auth) {
            // echo " - " . $auth->name . " - ";

            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                // if (1 > 0) {
                // echo "old!¬¬¬¬¬ !!!! ----";
                $client = Client::first();
                $http = new GuzzleHttpCLient();


                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com'

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                // echo $body;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body
                ]);
                $data = Utils::jsonDecode($response->getBody(), true);
                // dd($data);
                $date = new DateTime();
                $date = $date->modify("+19 minutes");
                $auth->update(['access_token' => $data['access_token'], 'refresh_token' => $data['refresh_token'], 'expire_date' => $date]);
            }
        }
    }

    public function updateStanding()
    {
        $token = Auth::where('flag_standing', 0)->first();
        if ($token == null) {
            Auth::where('flag_standing', 1)->update(['flag_standing' => 0]);
            $token = Auth::where('flag_standing', 0)->first();
            $token->update(['flag_note' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        } else {
            $token->update(['flag_standing' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        }

        $response = Http::withToken($token->access_token)->withHeaders([
            'Content-Type' => 'application/json',
            "Accept" => "application/json"
        ])->get($url);

        $standings = $response->collect();
        foreach ($standings as $standing) {
            $stand = collect($standing);
            if ($stand->get('standing') > 0) {
                $color = 2;
            } else {
                $color = 1;
            };

            if ($stand->get('contact_type') == "aliiance") {
                Alliance::where('id', $stand->get('contact_id'))->update([
                    'color' => $color,
                    'standing' => $stand->get('standing')
                ]);
            }

            if ($stand->get('contact_type') == "corporation") {
                Corp::where('id', $stand->get('contact_id'))->update([
                    'color' => $color,
                    'standing' => $stand->get('standing')
                ]);
            }
        }
        Alliance::where('color', '0')->update(['color' => 1]);
        Alliance::whereNull('standing')->update(['standing' => 0]);
        Alliance::where('id', '1354830081')->update(['standing' => 10, 'color' => 3]);
        Corp::whereNull('standing')->update(['standing' => 0]);
        Corp::where('color', '0')->update(['color' => 1]);
    }
}
