<?php

namespace App\Http\Controllers;

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
            $this->addNewLocal($text);
        } else {

            $this->addNewDscan($text);
        }
    }

    public function checkInputUpdate(Request $request, $link)
    {
        $text = $request->text;
        $lines = explode("\n", $text);

        if (count(explode("\t", $lines[0])) == 1) {
            $this->updateLocal($text, $link);
        } else {
            $this->updateDscan($text, $link);
        }
    }

    public function addNewLocal($local)
    {
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
        $dScanCharIds = DscanLocal::where('dscan_id', $newDscan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIds)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }

        return response()->json([
            'link' => $newDscan->link,
        ]);
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
        $newTotal = new DscanTotal();
        $newTotal->dscan_id = $newDscan->id;
        $newTotal->save();

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
                $newDscanItem->distance_value = 0; // "9.1";
                $newDscanItem->distance_unit = "m";
            }
            $newDscanItem->save();
            $item = Item::whereId($columns[0])->first();
            $group = Group::whereId($item->group_id)->first();
            $category = Categorie::whereId($group->category_id)->first();

            if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                $on = true;
            }

            if ($newDscanItem->distance_unit == "m") {
                $on = true;
            }

            $totals = $newTotal->totals;

            $current = $totals['items']['new'][$item->item_name]['group_id'] = $group->id;
            $current = $totals['items']['new'][$item->item_name]['category_id'] = $category->id;
            $current = $totals['items']['new'][$item->item_name]['item_name'] = $item->item_name;
            $current = $totals['items']['new'][$item->item_name]['item_id'] = $item->id;
            $newTotal->totals = $current;


            $current = $totals['groups']['new'][$group->name]['category_id'] = $category->id;
            $current = $totals['groups']['new'][$group->name]['group_id'] = $group->id;
            $current = $totals['groups']['new'][$group->name]['group_name'] = $group->name;
            $newTotal->totals = $current;

            $current = $totals['categories']['new'][$category->name]['category_id'] = $category->id;
            $current = $totals['categories']['new'][$category->name]['category_name'] = $category->name;
            $newTotal->totals = $current;


            if ($on) {
                $current = $totals['items']['new'][$item->item_name]['on'] ?? 0;
            } else {
                $current = $totals['items']['new'][$item->item_name]['off'] ?? 0;
            }

            $new = $current + 1;
            if ($on) {
                $totals['items']['new'][$item->item_name]['on'] = $new;
            } else {

                $totals['items']['new'][$item->item_name]['off'] = $new;
            }
            $newTotal->totals = $totals;

            $current = $totals['items']['new'][$item->item_name]['total'] ?? 0;
            $new = $current + 1;
            $totals['items']['new'][$item->item_name]['total'] = $new;

            if ($on) {
                $current = $totals['categories']['new'][$category->name]['on'] ?? 0;
            } else {

                $current = $totals['categories']['new'][$category->name]['off'] ?? 0;
            }


            $new = $current + 1;
            if ($on) {
                $totals['categories']['new'][$category->name]['on'] = $new;
            } else {

                $totals['categories']['new'][$category->name]['off'] = $new;
            }
            $newTotal->totals = $totals;

            $current = $totals['categories']['new'][$category->name]['total'] ?? 0;
            $new = $current + 1;
            $totals['categories']['new'][$category->name]['total'] = $new;


            if ($on) {
                $current = $totals['groups']['new'][$group->name]['on'] ?? 0;
            } else {

                $current = $totals['groups']['new'][$group->name]['off'] ?? 0;
            }

            $new = $current + 1;

            if ($on) {
                $totals['groups']['new'][$group->name]['on'] = $new;
            } else {

                $totals['groups']['new'][$group->name]['off'] = $new;
            }
            $newTotal->totals = $totals;

            $current = $totals['groups']['new'][$group->name]['total'] ?? 0;
            $new = $current + 1;
            $totals['groups']['new'][$group->name]['total'] = $new;
            $newTotal->totals = $totals;
            $newTotal->save();

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

        return [
            'link' => $newDscan->link
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return getDscanInfo($id);
        // if (Dscan::whereLink($id)->exists()) {

        // } else {
        //     return loadDscanHistory($id);
        // }
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
        $newTotal = DscanTotal::where('dscan_id', $dScan->id)->first();

        $totals = $newTotal->totals;
        $newItem = $totals['items']['new'];
        $totals['items']['old'] = $newItem;
        $totals['items']['new'] = [];
        $newCategory = $totals['categories']['new'];
        $totals['categories']['old'] = $newCategory;
        $totals['categories']['new'] = [];
        $newGroup = $totals['groups']['new'];
        $totals['groups']['old'] = $newGroup;
        $totals['groups']['new'] = [];
        $newTotal->totals = $totals;
        $newTotal->save();

        DscanItem::where('dscan_id', $dScan->id)->delete();

        foreach ($rows as $row) {
            $on = false;
            $columns = explode("\t", $row);
            $newDscanItem = new DscanItem();
            $newDscanItem->dscan_id = $dScan->id;
            $newDscanItem->item_id = $columns[0];
            $newDscanItem->name = $columns[1];
            $components = explode(" ", $columns[3]);
            if (count($components) > 1) {
                $newDscanItem->distance_value = $components[0]; // "9.1";
                $newDscanItem->distance_unit = $components[1];
            } else {
                $newDscanItem->distance_value = 0; // "9.1";
                $newDscanItem->distance_unit = "m";
            }
            $newDscanItem->save();
            $item = Item::whereId($columns[0])->first();
            $group = Group::whereId($item->group_id)->first();
            $category = Categorie::whereId($group->category_id)->first();

            if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                $on = true;
            }

            if ($newDscanItem->distance_unit == "m") {
                $on = true;
            }

            $totals = $newTotal->totals;

            $current = $totals['items']['new'][$item->item_name]['group_id'] = $group->id;
            $current = $totals['items']['new'][$item->item_name]['category_id'] = $category->id;
            $current = $totals['items']['new'][$item->item_name]['item_name'] = $item->item_name;
            $current = $totals['items']['new'][$item->item_name]['item_id'] = $item->id;
            $newTotal->totals = $current;


            $current = $totals['groups']['new'][$group->name]['category_id'] = $category->id;
            $current = $totals['groups']['new'][$group->name]['group_id'] = $group->id;
            $current = $totals['groups']['new'][$group->name]['group_name'] = $group->name;
            $newTotal->totals = $current;

            $current = $totals['categories']['new'][$category->name]['category_id'] = $category->id;
            $current = $totals['categories']['new'][$category->name]['category_name'] = $category->name;
            $newTotal->totals = $current;


            if ($on) {
                $current = $totals['items']['new'][$item->item_name]['on'] ?? 0;
            } else {
                $current = $totals['items']['new'][$item->item_name]['off'] ?? 0;
            }

            $new = $current + 1;
            if ($on) {
                $totals['items']['new'][$item->item_name]['on'] = $new;
            } else {

                $totals['items']['new'][$item->item_name]['off'] = $new;
            }
            $newTotal->totals = $totals;
            $newTotal->save();

            $current = $totals['items']['new'][$item->item_name]['total'] ?? 0;
            $new = $current + 1;
            $totals['items']['new'][$item->item_name]['total'] = $new;
            $newTotal->save();

            if ($on) {
                $current = $totals['categories']['new'][$category->name]['on'] ?? 0;
            } else {

                $current = $totals['categories']['new'][$category->name]['off'] ?? 0;
            }


            $new = $current + 1;
            if ($on) {
                $totals['categories']['new'][$category->name]['on'] = $new;
            } else {

                $totals['categories']['new'][$category->name]['off'] = $new;
            }
            $newTotal->totals = $totals;
            $newTotal->save();

            $current = $totals['categories']['new'][$category->name]['total'] ?? 0;
            $new = $current + 1;
            $totals['categories']['new'][$category->name]['total'] = $new;
            $newTotal->save();


            if ($on) {
                $current = $totals['groups']['new'][$group->name]['on'] ?? 0;
            } else {

                $current = $totals['groups']['new'][$group->name]['off'] ?? 0;
            }

            $new = $current + 1;

            if ($on) {
                $totals['groups']['new'][$group->name]['on'] = $new;
            } else {

                $totals['groups']['new'][$group->name]['off'] = $new;
            }
            $newTotal->totals = $totals;
            $newTotal->save();

            $current = $totals['groups']['new'][$group->name]['total'] ?? 0;
            $new = $current + 1;
            $totals['groups']['new'][$group->name]['total'] = $new;
            $newTotal->totals = $totals;
            $newTotal->save();

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

        return [
            'data' => $this->show($dScan->link),
        ];
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
