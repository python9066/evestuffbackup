<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcMoveDelete;
use App\Events\RcMoveUpdate;
use App\Events\RcSheetUpdate;
use App\Events\StationAttackMessageUpdate;
use App\Events\StationCoreUpdate;
use App\Events\StationDeadCoord;
use App\Events\StationDeadStationSheet;
use App\Events\StationMessageUpdate;
use App\Events\StationNotificationDelete;
use App\Events\StationNotificationUpdate;
use App\Events\StationSheetUpdate;
use App\Events\StationUpdateCoord;
use App\Events\WelpSheetUpdate;
use App\Models\Alliance;
use App\Models\ChillStationRecords;
use App\Models\Corp;
use App\Models\Item;
use App\Models\RcStationRecords;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationStatus;
use App\Models\System;
use App\Models\WelpStationRecords;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function updateStationSheetWebway($id)
    {
        $station_ids = Station::where('system_id', $id)->pluck('id');

        foreach ($station_ids as $id) {
            $message = StationRecordsSolo(6, $id);
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationSheetUpdate($flag));
        }
    }

    public function stationSheet()
    {
        return ['stations' =>  getAllallowedStations()];
    }

    public function loadStationData()
    {
        $coreData = [];
        $stations = Station::all();
        foreach ($stations as $station) {
            if ($station->r_cored == 1) {
                $core = 'Yes';
            } else {
                $core = 'No';
            }

            $taskFlag = System::where('id', $station->system_id)->value('task_flag');

            $data1 = [
                'task_flag' => $taskFlag,
                'station_id' => $station->id,
                'cored' => $core,
            ];

            array_push($coreData, $data1);
        }

        $items = [];
        $joins = StationItemJoin::all();
        foreach ($joins as $join) {
            $name = StationItems::where('id', $join->station_item_id)->first();
            $data = [
                'station_id' => $join->station_id,
                'item_name' => $name->item_name,
                'item_id' => $name->id,
            ];
            array_push($items, $data);
        }

        return [
            'cores' => $coreData,
            'fit' => Station::where('r_hash', '!=', null)->get(),
            'items' => $items,
        ];
    }

    public function reconRegionPull()
    {
        Artisan::call('update:reconstations');
    }

    public function taskRequest(Request $request)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $message = [
            'station_id' => $request->station_id,
            'task_flag' => 1,
        ];

        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationCoreUpdate($flag));

        $s = System::where('id', $request->system_id)->get();
        foreach ($s as $s) {
            $s->update(['task_flag' => 1]);
        }
        $url = 'https://recon.gnf.lt/api/task/add';
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TASK_TOKEN', ($variables && array_key_exists('RECON_TASK_TOKEN', $variables)) ? $variables['RECON_TASK_TOKEN'] : 'DANCE2'),

        ];

        $body = [
            'system' => $request->system_name,
            'task' => $request->structure_name,
            'username' => $request->username,
        ];
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $body,
            'http_errors' => false,
        ]);
    }

    public static function reconPullbyname(Request $request)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

        $name = preg_replace("/\([^\)]+\)(\R|$)/", '$1', $request->stationName);
        $name = rtrim($name);
        // dd($name);
        $url = 'https://recon.gnf.lt/api/structure/' . $name;

        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),
        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        // dd($response);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($response->getStatusCode() == 200) {
            if ($stationdata == 'Error, Structure Not Found') {
                $stationCheck = station::where('name', $name)->get();
                $check = $stationCheck->count();
                if ($check > null) {
                    $data = [];
                } else {
                    $data = [
                        'state' => 2,
                        'station_name' => $name,
                    ];

                    return $data;
                }
            } else {
                $checkifthere = Station::find($stationdata['str_structure_id']);
                $showMain = 0;
                $showChill = 0;
                $showRcMove = 0;
                $showWelp = 0;
                if ($checkifthere) {
                    $showMain = $checkifthere->show_on_main;
                    $showChill = $checkifthere->show_on_chill;
                    $showRcMove = $checkifthere->show_on_rc_move;
                    $showWelp = $checkifthere->show_on_welp;
                    if ($request->show == 1) {
                        $showMain = 1;
                    }
                    if ($request->show == 2) {
                        $showChill = 1;
                    }
                    if ($request->show == 3) {
                        $showRcMove = 1;
                    }

                    if ($request->show == 4) {
                        $showWelp = 1;
                    }
                }

                $core = 0;
                if ($stationdata['str_cored'] == 'Yes') {
                    $core = 1;
                }
                $standing = 0;
                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $alliance = Alliance::where('id', $corp->alliance_id)->first();
                if ($alliance) {
                    if ($corp->standing > $alliance->standing) {
                        $standing = $corp->standing;
                    } else {
                        $standing = $alliance->standing;
                    }
                } else {
                    $standing = $corp->standing;
                }

                Station::updateOrCreate(['id' => $stationdata['str_structure_id']], [
                    'name' => $stationdata['str_name'],
                    'standing' => $standing,
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'alliance_id' => $stationdata['str_owner_alliance_id'] ?? 0,
                    'item_id' => $stationdata['str_type_id'],
                    'text' => null,
                    'station_status_id' => 10,
                    'user_id' => null,
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
                    'r_updated_at' => $stationdata['updated_at'],
                    'r_fitted' => $stationdata['str_has_no_fitting'],
                    'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                    'r_hyasyoda' => $stationdata['str_hyasyoda'],
                    'r_invention' => $stationdata['str_invention'],
                    'r_manufacturing' => $stationdata['str_manufacturing'],
                    'r_research' => $stationdata['str_research'],
                    'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                    'r_biochemical' => $stationdata['str_biochemical'],
                    'r_hybrid' => $stationdata['str_hybrid'],
                    'r_moon_drilling' => $stationdata['str_moon_drilling'],
                    'r_reprocessing' => $stationdata['str_reprocessing'],
                    'r_point_defense' => $stationdata['str_point_defense'],
                    'r_dooms_day' => $stationdata['str_dooms_day'],
                    'r_guide_bombs' => $stationdata['str_guide_bombs'],
                    'r_anti_cap' => $stationdata['str_anti_cap'],
                    'r_anti_subcap' => $stationdata['str_anti_subcap'],
                    'r_t2_rigged' => $stationdata['str_t2_rigged'],
                    'r_cloning' => $stationdata['str_cloning'],
                    'r_composite' => $stationdata['str_composite'],
                    'r_cored' => $core,
                    'show_on_main' => $showMain,
                    'show_on_chill' => $showChill,
                    'show_on_rc_move' => $showRcMove,
                    'show_on_welp' => $showWelp,
                    'added_by_user_id' => Auth::id(),
                    'added_from_recon' => 1,
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    }
                }

                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $item = Item::where('id', $stationdata['str_type_id'])->first();
                $system = System::where('id', $stationdata['str_system_id'])->first();
                $data = [];
                $data = [
                    'state' => 3,
                    'station_id' => $stationdata['str_structure_id'],
                    'station_name' => $stationdata['str_name'],
                    'structure_name' => $item->item_name,
                    'structure_id' => $item->id,
                    'system_name' => $system->system_name,
                    'system_id' => $system->id,
                    'corp_ticker' => $corp->ticker,
                    'corp_id' => $corp->id,
                ];

                return $data;
            }
        } else {
            $stationCheck = station::where('name', $name)->first();
            if ($stationCheck) {
                $station = Station::where('id', $stationCheck->id)->first();

                return $data = [
                    'state' => 3,
                    'station_id' => $station->id,
                    'station_name' => $station->name,
                    'structure_name' => $station->item->item_name,
                    'structure_id' => $station->id,
                    'system_name' => $station->system->system_name,
                    'system_id' => $station->system_id,
                    'corp_ticker' => $station->corp->ticker,
                    'corp_id' => $station->corp_id,

                ];
            } else {
                $data = [
                    'state' => 2,
                    'station_name' => $name,
                ];

                return $data;
            }
        }
    }

    public static function editStationNameReconCheck(Request $request, $id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $oldName = Station::where('id', $id)->value('name');

        $name = preg_replace("/\([^\)]+\)(\R|$)/", '$1', $request->stationName);
        $name = rtrim($name);
        // dd($name);
        $url = 'https://recon.gnf.lt/api/structure/' . $name;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        // dd($response->getBody());

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($response->getStatusCode() == 200) {
            if ($stationdata == 'Error, Structure Not Found') {
                $s = Station::where('id', $id)->get();
                foreach ($s as $s) {
                    $s->update(['name' => $name, 'added_from_recon' => 0]);
                }
            } else {
                $checkifthere = Station::find($stationdata['str_structure_id']);
                $showMain = 0;
                $showChill = 0;
                $showRcMove = 0;
                $showWelp = 0;
                if ($checkifthere) {
                    $showMain = $checkifthere->show_on_main;
                    $showChill = $checkifthere->show_on_chill;
                    $showRcMove = $checkifthere->show_on_rc_move;
                    $showWelp = $checkifthere->show_on_welp;
                    if ($request->show == 1) {
                        $showMain = 1;
                    }
                    if ($request->show == 2) {
                        $showChill = 1;
                    }
                    if ($request->show == 3) {
                        $showRcMove = 1;
                    }

                    if ($request->show == 4) {
                        $showWelp = 1;
                    }
                }

                $core = 0;
                if ($stationdata['str_cored'] == 'Yes') {
                    $core = 1;
                }

                $standing = 0;
                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $alliance = Alliance::where('id', $corp->alliance_id)->first();
                if ($alliance) {
                    if ($corp->standing > $alliance->standing) {
                        $standing = $corp->standing;
                    } else {
                        $standing = $alliance->standing;
                    }
                } else {
                    $standing = $corp->standing;
                }
                // dd($stationdata);
                Station::updateOrCreate(['id' => $id], [
                    'id' => $stationdata['str_structure_id'],
                    'standing' => $standing,
                    'name' => $stationdata['str_name'],
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'item_id' => $stationdata['str_type_id'],
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
                    'r_updated_at' => $stationdata['updated_at'],
                    'r_fitted' => $stationdata['str_has_no_fitting'],
                    'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                    'r_hyasyoda' => $stationdata['str_hyasyoda'],
                    'r_invention' => $stationdata['str_invention'],
                    'r_manufacturing' => $stationdata['str_manufacturing'],
                    'r_research' => $stationdata['str_research'],
                    'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                    'r_biochemical' => $stationdata['str_biochemical'],
                    'r_hybrid' => $stationdata['str_hybrid'],
                    'r_moon_drilling' => $stationdata['str_moon_drilling'],
                    'r_reprocessing' => $stationdata['str_reprocessing'],
                    'r_point_defense' => $stationdata['str_point_defense'],
                    'r_dooms_day' => $stationdata['str_dooms_day'],
                    'r_guide_bombs' => $stationdata['str_guide_bombs'],
                    'r_anti_cap' => $stationdata['str_anti_cap'],
                    'r_anti_subcap' => $stationdata['str_anti_subcap'],
                    'r_t2_rigged' => $stationdata['str_t2_rigged'],
                    'r_cloning' => $stationdata['str_cloning'],
                    'r_composite' => $stationdata['str_composite'],
                    'r_cored' => $core,
                    'show_on_main' => $showMain,
                    'show_on_chill' => $showChill,
                    'show_on_rc_move' => $showRcMove,
                    'show_on_welp' => $showWelp,
                    'added_from_recon' => 1,
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    }
                }

                $message = stationRecordSolo($stationdata['str_structure_id']);

                $flag = collect([
                    'message' => $message,
                ]);

                broadcast(new RcMoveUpdate($flag));
            }
        } else {
            $s = Station::where('id', $id)->get();
            foreach ($s as $s) {
                $s->update(['name' => $name, 'added_from_recon' => 0]);
            }

            $message = stationRecordSolo($id);


            $flag = collect([
                'message' => $message,
            ]);

            broadcast(new RcMoveUpdate($flag));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request#
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $id = Station::where('added_from_recon', 0)->max('id');
        if ($id == null) {
            $id = 1;
        } else {
            $id = $id + 1;
        }

        $new = new Station();
        $new->id = $id;
        $new->name = $request->name;
        $new->system_id = $request->system_id;
        $new->corp_id = $request->corp_id;
        $new->item_id = $request->item_id;
        $new->station_status_id = $request->station_status_id;
        $new->out_time = $request->out_time;
        $new->status_update = now();
        $new->timestamp = now();
        $new->timer_image_link = $request->timer_image_link;
        $new->added_by_user_id = $user->id;
        $new->r_cored = 0;
        $new->log_helper = 2;

        if ($user->can('add_timer')) {
            $new->show_on_rc_move = 0;
        } else {
            $new->show_on_rc_move = 1;
        }



        $corp = Corp::where('id', $new->corp_id)->first();
        $corpStanding = $corp->standing;
        $allianceStanding = null;
        if (Alliance::where('id', $new->alliance_id)->first()) {
            $alliance = Alliance::where('id', $new->alliance_id)->first();
            $allianceStanding = $alliance->standing;
        }

        if ($allianceStanding) {
            if ($corpStanding > $allianceStanding) {
                $standing = $corpStanding;
            } else {
                $standing = $allianceStanding;
            }
        } else {
            $standing = $corpStanding;
        }

        $new->standing = $standing;
        $new->save();
    }


    public function addStationTimer(Request $request, $id)
    {
        $user = Auth::user();
        $station = Station::whereId($id)->first();
        $station->station_status_id = $request->station_status_id;
        $station->out_time = $request->out_time;
        $station->status_update = now();
        $station->timestamp = now();
        $station->timer_image_link = $request->timer_image_link;
        $station->added_by_user_id = $user->id;
        $station->r_cored = 0;
        $station->log_helper = 2;
        $station->show_on_coord = 0;
        if ($user->can('add_timer')) {
            $station->show_on_rc_move = 0;
        } else {
            $station->show_on_rc_move = 1;
        }

        $station->save();

        $message = StationRecordsSolo(6, $id);
        $flag = collect([
            'flag' => $message,
        ]);
        broadcast(new StationSheetUpdate($flag));

        $flag = collect([
            'id' => $id,
        ]);
        broadcast(new StationDeadStationSheet($flag));
    }


    public function editStation(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('finish_move_timers')) {
            $station = Station::whereId($id)->first();
            $station->name = $request->name;
            $station->system_id = $request->system_id;
            $station->corp_id = $request->corp_id;
            $station->item_id = $request->item_id;
            $station->station_status_id = $request->station_status_id;
            $station->out_time = $request->out_time;
            $station->timer_image_link = $request->timer_image_link;
            $station->status_update = now();
            $station->timestamp = now();
            $station->log_helper = 3;
            $station->show_on_coord = 0;

            $station->save();

            $message = StationRecordsSolo(6, $id);
            $flag = collect([
                'flag' => $message,
            ]);
            broadcast(new StationSheetUpdate($flag));

            $message = stationRecordSolo($id);
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcMoveUpdate($flag));

            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new StationDeadStationSheet($flag));
        } else {
            return null;
        }
    }

    public function updateTimerStatus(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('finish_move_timers')) {
            $station = Station::whereId($id)->first();
            $station->show_on_rc_move = 0;
            if ($request->status == 2) {
                $station->station_status_id = 16;
                $station->out_time = null;
                $station->status_update = now();
                $station->log_helper = 4;
                $station->timer_image_link = null;
            } else {
                $station->log_helper = 5;
            }
            $station->save();

            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new RcMoveDelete($flag));
        } else {
            return null;
        }
    }

    public function updateAttackMessage(Request $request, $id)
    {
        // dd($request->notes);

        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update($request->all());
        }

        $message = stationRecordSolo($id);

        if ($message->under_attack == 0) {
            $type = 2;
        } else {
            $type = 1;
        }
        $flag = collect([
            'type' => $type,
            'message' => $message,
            'id' => $id,
        ]);

        // dd($request, $id, $flag);
        broadcast(new StationAttackMessageUpdate($flag))->toOthers();
    }

    public function updateMessage(Request $request, $id)
    {
        // dd($request->notes);

        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update($request->all());
        }

        $message = stationRecordSolo($id);
        $flag = collect([
            'message' => $message,
            'id' => $id,
        ]);

        // dd($request, $id, $flag);
        broadcast(new StationMessageUpdate($flag))->toOthers();
    }

    public function rcMoveDone($id)
    {
        $statusID = Station::where('id', $id)->value('station_status_id');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update([
                'show_on_rc_move' => 0,
                'show_on_rc' => 1,
                'station_status_id' => $statusID,
                'timer_image_link' => null,
            ]);
        }
        $message = stationRecordSolo($id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcMoveUpdate($flag));

        $message = RcStationRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));

        $message = StationRecordsSolo(4, $id);
        $flag = collect([
            'message' => $message,
        ]);

        broadcast(new RcSheetUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oldStatusID = Station::where('id', $id)->value('station_status_id');
        $oldStatusName = StationStatus::where('id', $oldStatusID)->value('name');
        $oldStatusName = str_replace('Upcoming - ', '', $oldStatusName);
        $newStatusID = $request->station_status_id;
        $newStatusName = StationStatus::where('id', $newStatusID)->value('name');
        $newStatusName = str_replace('Upcoming - ', '', $newStatusName);
        $s = Station::whereId($id)->first();
        $s->update($request->all());
        $s->added_by_user_id = Auth::id();
        $s->rc_id = null;
        $s->rc_fc_id = null;
        $s->rc_gsol_id = null;
        $s->rc_recon_id = null;
        $s->save();

        $message = StationRecordsSolo(6, $id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationSheetUpdate($flag));

        $RCmessage = StationRecordsSolo(4, $id);
        if ($RCmessage) {
            $flag = collect([
                'message' => $RCmessage,
            ]);
        } else {
            $stationID = intval($id);
            $RCmessageSend = collect([

                'id' => $stationID,
                'show_on_rc' => 0,
            ]);

            $flag = collect([
                'message' => $RCmessageSend,
            ]);
        }

        broadcast(new RcSheetUpdate($flag));

        $RCmessage = StationRecordsSolo(6, $id);
        if ($RCmessage) {
            $flag = collect([
                'message' => $RCmessage,
            ]);
        } else {
            $stationID = intval($id);
            $RCmessageSend = collect([

                'id' => $stationID,
                'show_on_rc' => 0,
            ]);

            $flag = collect([
                'message' => $RCmessageSend,
            ]);
        }

        broadcast(new StationSheetUpdate($flag));
    }

    public function updateStationSheet(Request $request, $id)
    {
        $s = Station::whereId($id)->first();
        $s->station_status_id = $request->station_status_id;
        $s->show_on_rc = 0;
        $s->show_on_coord = 1;
        $s->rc_id = null;
        $s->rc_fc_id = null;
        $s->rc_gsol_id = null;
        $s->rc_recon_id = null;
        $s->log_helper = 1;
        $s->save();

        $message = StationRecordsSolo(6, $id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationSheetUpdate($flag));
    }

    public function editUpdate(Request $request, $id)
    {
        // dd($id);
        $now = now();

        $RCmessage = StationRecordsSolo(4, $id);
        if ($RCmessage) {
            $RCmessageSend = [
                'id' => $RCmessage->id,
                'show_on_rc' => 0,
            ];
            $flag = collect([
                'message' => $RCmessageSend,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        // $RCmessage = ChillStationRecords::where('id', $id)->first();
        // if ($RCmessage) {
        //     $RCmessageSend = [
        //         'id' => $RCmessage->id,
        //         'show_on_rc' => 0,
        //     ];
        //     $flag = collect([
        //         'message' => $RCmessageSend,
        //     ]);
        //     broadcast(new ChillSheetUpdate($flag));
        // }

        // $RCmessage = WelpStationRecords::where('id', $id)->first();
        // if ($RCmessage) {
        //     $RCmessageSend = [
        //         'id' => $RCmessage->id,
        //         'show_on_rc' => 0,
        //     ];
        //     $flag = collect([
        //         'message' => $RCmessageSend,
        //     ]);
        //     broadcast(new WelpSheetUpdate($flag));
        // }

        $RCmessage = StationRecordsSolo(6, $id);
        if ($RCmessage) {
            $RCmessageSend = [
                'id' => $RCmessage->id,
                'show_on_coord' => 0,
            ];
            $flag = collect([
                'message' => $RCmessageSend,
            ]);
            broadcast(new StationSheetUpdate($flag));
        }

        $oldStation = Station::where('id', $id)->first();
        $oldStatus = StationStatus::where('id', $oldStation->station_status_id)->value('name');
        $oldStatus = str_replace('Upcoming - ', '', $oldStatus);

        $s = Station::find($id)->get();
        $s = Station::where('id', $id)->first();
        $s->station_status_id = $request->station_status_id;
        $s->out_time = $request->out_time;
        $s->timer_image_link = $request->timer_image_link;
        $s->rc_fc_id = $request->rc_fc_id;
        $s->rc_recon_id = $request->rc_recon_id;
        $s->rc_gsol_id = $request->rc_gsol_id;
        $s->show_on_rc_move = $request->show_on_rc_move;
        $s->show_on_rc = $request->show_on_rc;
        $s->show_on_coord = $request->show_on_coord;
        $s->status_update = $request->status_update;
        $s->save();


        $newStation = Station::where('id', $id)->first();
        $newStatus = StationStatus::where('id', $newStation->station_status_id)->value('name');
        $newStatus = str_replace('Upcoming - ', '', $newStatus);

        $message = stationRecordSolo($id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationNotificationUpdate($flag));
        broadcast(new StationUpdateCoord($flag));
        broadcast(new RcMoveUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));

        $message = StationRecordsSolo(6, $id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationSheetUpdate($flag));

        $message = StationRecordsSolo(4, $id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new RcSheetUpdate($flag));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = StationItemJoin::where('station_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $flag = collect([
            'id' => $id,
        ]);
        broadcast(new RcMoveDelete($flag));
        broadcast(new StationNotificationDelete($flag));
        broadcast(new StationDeadCoord($flag));
        broadcast(new StationDeadStationSheet($flag));
    }

    public function softDestroy($id)
    {
        $now = now();
        // $stationName = Station::find($id)->value('name');

        $RCmessage = StationRecordsSolo(4, $id);
        if ($RCmessage) {
            $RCmessageSend = [
                'id' => $RCmessage->id,
                'show_on_rc' => 0,
            ];
            $flag = collect([
                'message' => $RCmessageSend,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        // $RCmessage = ChillStationRecords::where('id', $id)->first();
        // if ($RCmessage) {
        //     $RCmessageSend = [
        //         'id' => $RCmessage->id,
        //         'show_on_chill' => 0,
        //     ];
        //     $flag = collect([
        //         'message' => $RCmessageSend,
        //     ]);
        //     broadcast(new ChillSheetUpdate($flag));
        // }

        // $RCmessage = WelpStationRecords::where('id', $id)->first();
        // if ($RCmessage) {
        //     $RCmessageSend = [
        //         'id' => $RCmessage->id,
        //         'show_on_welp' => 0,
        //     ];
        //     $flag = collect([
        //         'message' => $RCmessageSend,
        //     ]);
        //     broadcast(new WelpSheetUpdate($flag));
        // }

        $s = Station::where('id', $id)->get();

        foreach ($s as $s) {
            $s->update([
                'show_on_rc' => 0,
                'show_on_coord' => 1,
                'show_on_welp' => 0,
                'show_on_chill' => 0,
                'station_status_id' => 7,
                'rc_id' => null,
                'rc_fc_id' => null,
                'rc_gsol_id' => null,
                'rc_recon_id' => null,
            ]);
        }

        $newStatusID = 7;
        $newStatusName = StationStatus::where('id', $newStatusID)->value('name');
        $newStatusName = str_replace('Upcoming - ', '', $newStatusName);

        $message = stationRecordSolo($id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationNotificationUpdate($flag));
        broadcast(new StationUpdateCoord($flag));
    }
}
