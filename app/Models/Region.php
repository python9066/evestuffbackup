<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Region extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function systems()
    {
        return $this->hasMany(System::class);
    }

    public function constellations()
    {
        return $this->hasMany(Constellation::class);
    }

    public function moons()
    {
        return $this->hasMany(Moon::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
