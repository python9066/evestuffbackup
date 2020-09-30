<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $guarded =[];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function constellation()
    {
        return $this->belongsTo(Constellation::class);
    }

    public function timers()
    {
        return $this->hasMany(Structure::class);
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
        'region_id ' => 'integer',
        'constellation_id' => 'integer',
        'id' => 'integer',
        'adm' => 'double',
    ];

}
