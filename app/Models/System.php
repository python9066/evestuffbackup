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
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_systems');
    }

    public function newNodes()
    {
        return $this->hasMany(NewSystemNode::class, 'system_id', 'id');
    }


    public function scoutUser()
    {
        return $this->belongsTo(User::class, 'scout_id', 'id');
    }

    public function checkUser()
    {
        return $this->belongsTo(User::class, 'checked_id', 'id');
    }



    protected $casts = [
        'region_id ' => 'integer',
        'constellation_id' => 'integer',
        'id' => 'integer',
        'adm' => 'double',
    ];
}
