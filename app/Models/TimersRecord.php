<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TimersRecord extends Model
{
    use LogsActivity;
    protected $table = 'timer_records';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }





    protected $casts = [
        'region_id' => 'integer',
        'constellation_id' => 'integer',
        'system_id' => 'integer',
        'alliance_id' => 'integer',
        'region_id' => 'integer',
        'standing' => 'double',
        'color' => 'integer',
        'adm' => 'double',
        'status' => 'integer',
        'id' => 'integer',
    ];
}
