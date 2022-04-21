<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function notification()
    {
        return $this->hasOne(StationNotification::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function status()
    {
        return $this->belongsTo(StationStatus::class);
    }

    public function fc()
    {
        return $this->belongsTo(RcFcUsers::class, 'rc_fc_id', 'id');
    }
    public function recon()
    {
        return $this->belongsTo(RcFcUsers::class);
    }
    public function gsoluser()
    {
        return $this->belongsTo(RcGsolUsers::class);
    }


    public function corp()
    {
        return $this->belongsTo(Corp::class);
    }

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'corp_id' => 'integer',
        'alliance_id' => 'integer',
        'item_id' => 'integer',
        'user_id' => 'integer',
        'station_status_id' => 'integer',
        'gunner_id' => 'integer',
        'ammo_request_id' => 'integer',
        'r_capital_shipyard' => 'integer',
        'r_hyasyoda' => 'integer',
        'r_invention' => 'integer',
        'r_manufacturing' => 'integer',
        'r_research' => 'integer',
        'r_supercapital_shipyard' => 'integer',
        'r_biochemical' => 'integer',
        'r_hybrid' => 'integer',
        'r_moon_drilling' => 'integer',
        'r_reprocessing' => 'integer',
        'r_point_defense' => 'integer',
        'r_dooms_day' => 'integer',
        'r_anti_subcap' => 'integer',
        'r_guide_bombs' => 'integer',
        'r_anti_cap' => 'integer',
        'r_t2_rigged' => 'integer',
        'r_cloning' => 'integer',
        'r_composite' => 'integer',
        'r_cored' => 'integer',
        'show_on_main' => 'integer',
        'show_on_chill' => 'integer',
        'show_on_welp' => 'integer',
        'show_on_rc' => 'integer',
        'show_on_rc_move' => 'integer',
        'show_on_coord' => 'integer',
        'rc_fc_id' => 'integer',
        'rc_gsol_id' => 'integer',
        'rc_recon_id' => 'integer',
        'added_by_user_id' => 'integer',
        'corp_id' => 'integer',




    ];
}
