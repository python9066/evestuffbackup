<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RcStationRecords extends Model
{
    use HasFactory, LogsActivity;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    public function webway()
    {
        return $this->hasMany(WebWay::class, 'system_id', 'system_id');
    }



    protected $casts = [
        'alliance_id' => 'integer',
        'fc_user_id' => 'integer',
        'item_id' => 'integer',
        'rc_fc_id' => 'integer',
        'rc_recon_id' => 'integer',
        'recon_user_id' => 'integer',
        'status_id' => 'integer',
        'system_id' => 'integer',
        'out' => 'integer',
        'added_by_user_id' => 'integer',
        'show_on_chill' => 'integer',
        'show_on_main' => 'integer',
        'show_on_fc_move' => 'integer',
        'show_on_welp' => 'integer',
        'id' => 'integer',
        'added_by_user_id' => 'integer',
        'corp_id' => 'integer',


    ];
}
