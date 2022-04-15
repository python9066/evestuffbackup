<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCampaign extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function fleets()
    {
        return $this->belongsToMany(FleetType::class, 'key_fleet_joins');
    }
}
