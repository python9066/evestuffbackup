<?php

namespace App\Http\Controllers;

use App\Events\dScanSoloUpdate;
use App\Jobs\getLocalNamesJob;
use App\Models\Categorie;
use App\Models\Character;
use App\Models\Dscan;
use App\Models\DscanItem;
use App\Models\DscanLocal;
use App\Models\DscanTotal;
use App\Models\Group;
use App\Models\Item;
use App\Models\System;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DscanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function checkInputNew(Request $request)
    {
        $text = $request->text;
        $lines = explode("\n", $text);

        if (count(explode("\t", $lines[0])) == 1) {
            $data = $this->addNewLocal($text);
            return response()->json([
                'link' => $data,
            ]);
        } else {

            $data =  $this->addNewDscan($text);

            return  response()->json([
                'link' => $data,
            ]);
        }
    }

    public function checkInputUpdate(Request $request, $link)
    {
        makeDscanHistoy($link);
        $text = $request->text;
        $lines = explode("\n", $text);
        $data = null;

        if (count(explode("\t", $lines[0])) == 1) {
            $data =  $this->updateLocal($text, $link);
            $this->sendUpdateBoardCasts($data, $link);
            return response()->json([
                'data' => $data,
            ]);
        } else {

            $data =   $this->updateDscan($text, $link);
            $this->sendUpdateBoardCasts($data, $link);
            return   response()->json([
                'data' => $data,
            ]);
        }
    }

    public function sendUpdateBoardCasts($data, $link)
    {
        $message = $data['corpsTotal'];
        $flag = collect([
            'id' => $link,
            'flag' => 3,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['allianceTotal'];
        $flag = collect([
            'id' => $link,
            'flag' => 4,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['dscan'];
        $flag = collect([
            'id' => $link,
            'flag' => 5,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['history'];
        $flag = collect([
            'id' => $link,
            'flag' => 6,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['categoryTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 7,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['groupTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 8,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['itemTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 9,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();
    }

    public function addNewLocal($local)
    {
        $newDscan = new Dscan();
        $newDscan->user_id = Auth::user()->id;
        $newDscan->link = Str::uuid();
        $newDscan->save();

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
        $dScanCharIds = DscanLocal::where('dscan_id', $newDscan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIds)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }




        if (!$newDscan->system_id) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);
            $webwayURL = env('WEBWAY_URL2', ($variables && array_key_exists('WEBWAY_URL2', $variables)) ? $variables['WEBWAY_URL2'] : null);
            $dScanCharIdsSend = DscanLocal::where('dscan_id', $newDscan->id)->pluck('character_id');
            $res =  Http::withToken($webwayToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online python9066@gmail.com',
                ])->post($webwayURL, $dScanCharIdsSend);

            $info =  $res->json();
            if ($info['status']) {
                $newDscan->system_id = $info['system_id'];
                $newDscan->save();
            }
        }

        return $newDscan->link;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addNewDscan($dscan_results)
    {


        $newDscan = new Dscan();
        $newDscan->user_id = Auth::user()->id;
        $newDscan->link = Str::uuid();
        $newDscan->save();

        $rows = explode("\n", $dscan_results);
        // $newTotal = new DscanTotal();
        // $newTotal->dscan_id = $newDscan->id;
        // $newTotal->save();

        foreach ($rows as $row) {
            $on = false;
            $columns = explode("\t", $row);
            $newDscanItem = new DscanItem();
            $newDscanItem->dscan_id = $newDscan->id;
            $newDscanItem->item_id = $columns[0];
            $newDscanItem->name = $columns[1];
            $components = explode(" ", $columns[3]);
            if (count($components) > 1) {
                $newDscanItem->distance_value = $components[0]; // "9.1";
                $newDscanItem->distance_unit = $components[1];
            } else {
                $newDscanItem->distance_value = 1; // "9.1";
                $newDscanItem->distance_unit = "au";
            }
            if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                $on = true;
            }

            if ($newDscanItem->distance_unit == "m") {
                $on = true;
            }
            $newDscanItem->on_grid = $on;
            $newDscanItem->new = true;
            $newDscanItem->save();
            $item = Item::whereId($columns[0])->first();
            $group = Group::whereId($item->group_id)->first();
            if (!$newDscan->system_id) {
                $groupName = $item->group->name;
                if ($groupName == "Sun") {
                    $systemName = explode(" - ", $columns[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $newDscan->system_id = $system->id;
                    $newDscan->save();
                }

                if (
                    $group->id == 1406 ||
                    $group->id == 1876 ||
                    $group->id == 1657 ||
                    $group->id == 1404 ||
                    $group->id == 15
                ) {
                    $systemName = explode(" - ", $columns[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $newDscan->system_id = $system->id;
                    $newDscan->save();
                }
            }
        }



        return  $newDscan->link;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Dscan::whereLink($id)->exists()) {
            return getDscanInfo($id);
        } else {
            return loadDscanHistory($id);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDscan($dscan_results, $link)
    {


        $dScan = Dscan::whereLink($link)->first();
        $dScan->updated_by = Auth::user()->id;
        $dScan->save();



        $rows = explode("\n", $dscan_results);


        DscanItem::where('dscan_id', $dScan->id)->update(['updated' => false]);

        foreach ($rows as $row) {
            $on = false;
            $columns = explode("\t", $row);
            $dScanItem = DscanItem::where('dscan_id', $dScan->id)->where('item_id', $columns[0])->where('updated', false)->first();
            if ($dScanItem) {
                $dScanItem->updated = true;
                $dScanItem->new = false;
                $dScanItem->same = true;
                $components = explode(" ", $columns[3]);
                if (count($components) > 1) {
                    $dScanItem->distance_value = $components[0]; // "9.1";
                    $dScanItem->distance_unit = $components[1];
                } else {
                    $dScanItem->distance_value = 1; // "9.1";
                    $dScanItem->distance_unit = "au";
                }

                if ($dScanItem->distance_unit == "km" && $dScanItem->distance_value <= 8000) {
                    $on = true;
                }

                if ($dScanItem->distance_unit == "m") {
                    $on = true;
                }
                $dScanItem->on_grid = $on;
                $dScanItem->save();
                $item = Item::whereId($columns[0])->first();
                $group = Group::whereId($item->group_id)->first();
            } else {
                $newDscanItem = new DscanItem();
                $newDscanItem->dscan_id = $dScan->id;
                $newDscanItem->item_id = $columns[0];
                $newDscanItem->name = $columns[1];
                $newDscanItem->updated = true;
                $components = explode(" ", $columns[3]);
                if (count($components) > 1) {
                    $newDscanItem->distance_value = $components[0]; // "9.1";
                    $newDscanItem->distance_unit = $components[1];
                } else {
                    $newDscanItem->distance_value = 1; // "9.1";
                    $newDscanItem->distance_unit = "au";
                }
                if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                    $on = true;
                }

                if ($newDscanItem->distance_unit == "m") {
                    $on = true;
                }
                $newDscanItem->on_grid = $on;
                $newDscanItem->new = true;
                $newDscanItem->save();
            }

            $item = Item::whereId($columns[0])->first();
            $group = Group::whereId($item->group_id)->first();
            if (!$dScan->system_id) {
                $groupName = $item->group->name;
                if ($groupName == "Sun") {
                    $systemName = explode(" - ", $columns[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $dScan->system_id = $system->id;
                    $dScan->save();
                }

                if (
                    $group->id == 1406 ||
                    $group->id == 1876 ||
                    $group->id == 1657 ||
                    $group->id == 1404 ||
                    $group->id == 15
                ) {
                    $systemName = explode(" - ", $columns[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $dScan->system_id = $system->id;
                    $dScan->save();
                }
            }
        }

        DscanItem::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where(function ($query) {
                $query->where('new', true)
                    ->orWhere('same', true);
            })
            ->update([
                'new' => false,
                'same' => false,
                'left' => true,
                'updated' => true
            ]);

        DscanItem::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where('left', true)
            ->delete();

        return  $this->show($dScan->link);
    }

    public function updateLocal($local, string $id)
    {
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
        $dScanCharIDs = DscanLocal::where('dscan_id', $dScan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIDs)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }

        if (!$dScan->system_id) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);
            $webwayURL = env('WEBWAY_URL2', ($variables && array_key_exists('WEBWAY_URL2', $variables)) ? $variables['WEBWAY_URL2'] : null);
            $dScanCharIdsSend = DscanLocal::where('dscan_id', $dScan->id)->pluck('character_id');
            $res =  Http::withToken($webwayToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online python9066@gmail.com',
                ])->post($webwayURL, $dScanCharIdsSend);

            $info =  $res->json();
            if ($info['status']) {
                $dScan->system_id = $info['system_id'];
                $dScan->save();
            }
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
