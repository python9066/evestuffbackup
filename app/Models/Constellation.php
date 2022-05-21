<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Constellation extends Model
{
    use LogsActivity;
    protected $guarded = [];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    public function region()
    {
        return $this->belongsTO(Region::class);
    }


    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function moons()
    {
        return $this->hasMany(Moon::class);
    }

    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
    ];
}
