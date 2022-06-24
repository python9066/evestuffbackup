<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StationRecords extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $casts = [
        'id' => 'integer',
        'added_by_user_id' => 'integer',
        'ammo_request' => 'integer',
        'corp_id' => 'integer',
        'item_id' => 'integer',
        'standing' => 'integer',
        'station_status_id' => 'integer',
        'system_id' => 'integer',
        'under_attack' => 'integer',




    ];
}
