<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $guarded = [];

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

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function moons()
    {
        return $this->hasMany(Moon::class);
    }

    public function solasystem()
    {
        return $this->hasMany(CampaignSolaSystem::class);
    }

    public function chillStations()
    {
        return $this->hasMany(ChillStation::class);
    }

    public function reconTaskSystem()
    {
        return $this->hasMany(ReconTasks::class);
    }

    public function webway()
    {
        return $this->hasMany(WebWay::class, "system_id", "id");
    }

    public function newCampaigns()
    {
        return $this->hasManyThrough(NewCampaign::class, Constellation::class);
    }


    protected $casts = [
        'region_id ' => 'integer',
        'constellation_id' => 'integer',
        'id' => 'integer',
        'adm' => 'double',
    ];
}
