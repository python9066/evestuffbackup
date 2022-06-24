<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
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
    ];
}
