<?php

namespace App\Http\Controllers;

use App\Jobs\getLocalNamesJob;
use App\Models\Character;
use App\Models\Dscan;
use App\Models\DscanLocal;
use App\Models\DscanTotal;
use Auth;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Termwind\Components\Dd;

class DscanLocalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Add New Local Dscan.
     */
    public function addNewLocal(Request $request)
    {
        $local = $request->local;

        $newDscan = new Dscan();
        $newDscan->user_id = Auth::user()->id;
        $newDscan->link = Str::uuid();
        $newDscan->save();
        $newDscanTotal = new DscanTotal();
        $newDscanTotal->dscan_id = $newDscan->id;
        $newDscanTotal->save();

        $rows = explode("\n", $local);

        $newNames = [];
        foreach ($rows as $key => $row) {
            $pullName =   $rows[$key] = trim($row);
            $char = Character::where('name', $pullName)->whereNotNull('corp_id')->first();
            if (!$char) {
                $newNames[] = $pullName;
            } else {
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $newDscan->id;
                $newDscanLocal->character_id = $char->id;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }


        $newNamesChunks = array_chunk($newNames, 500);

        $responses = [];

        foreach ($newNamesChunks as $chunk) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online python9066@gmail.com',
            ])
                ->withBody(json_encode($chunk), 'application/json')
                ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

            if ($response->successful()) {
                $responses[] = $response->json();
            }
        }
        foreach ($responses as $response) {
            $chars = $response['characters'];

            foreach ($chars as $char) {
                $newChar = Character::whereId($char['id'])->first() ?? new Character();
                $newChar->id = $char['id'];
                $newChar->name = $char['name'];
                $newChar->save();
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $newDscan->id;
                $newDscanLocal->character_id = $newChar->id;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }

        $charIDs = Character::whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }

        return response()->json([
            'link' => $newDscan->link,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $local = $request->local;

        $dScan = Dscan::where('link', $id)->first();
        $dScan->updated_by = Auth::user()->id;
        $dScan->updated_at = now();
        $dScan->save();

        DscanLocal::where('dscan_id', $dScan->id)->update(['updated' => false]);

        $rows = explode("\n", $local);

        $newNames = [];
        foreach ($rows as $key => $row) {
            $pullName =   $rows[$key] = trim($row);
            $char = Character::where('name', $pullName)->whereNotNull('corp_id')->first();
            if (!$char) {
                $newNames[] = $pullName;
            } else {

                $checkForUserInOldScan = DscanLocal::where('character_id', $char->id)->get();
                foreach ($checkForUserInOldScan as $oldScan) {


                    if ($oldScan->dscan_id == $dScan->id) {
                        $oldScan->same = true;
                        $oldScan->new = false;
                        $oldScan->left = false;
                        $oldScan->updated = true;
                        $oldScan->save();
                    }
                }


                if (!DscanLocal::where('dscan_id', $dScan->id)->where('character_id', $char->id)->first()) {
                    $newDscanLocal = new DscanLocal();
                    $newDscanLocal->dscan_id = $dScan->id;
                    $newDscanLocal->character_id = $char->id;
                    $newDscanLocal->updated = true;
                    $newDscanLocal->new = true;
                    $newDscanLocal->save();
                }
            }
        }

        DscanLocal::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where('left', true)
            ->delete();

        DscanLocal::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->update(['left' => true, 'new' => false, 'same' => false, 'updated' => true]);




        $newNamesChunks = array_chunk($newNames, 500);

        $responses = [];

        foreach ($newNamesChunks as $chunk) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online python9066@gmail.com',
            ])
                ->withBody(json_encode($chunk), 'application/json')
                ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

            if ($response->successful()) {
                $responses[] = $response->json();
            }
        }
        foreach ($responses as $response) {
            $chars = $response['characters'];

            foreach ($chars as $char) {
                $newChar = new Character();
                $newChar->id = $char['id'];
                $newChar->name = $char['name'];
                $newChar->save();
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $dScan->id;
                $newDscanLocal->character_id = $newChar->id;
                $newDscanLocal->updated = true;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }

        $charIDs = Character::whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }

        return getDscanInfo($dScan->link);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
