<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Corp extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $guarded = [];

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function chillStations()
    {
        return $this->hasMany(ChillStation::class);
    }



    protected $casts = [
        'id' => 'integer',
        'alliance_id' => 'integer',
        'active' => 'integer',
        'standingstanding' => 'double',
        'active' => 'integer',
        'color' => 'integer',
    ];
}
