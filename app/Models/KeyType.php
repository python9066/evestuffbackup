<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fleets()
    {
        return $this->belongsToMany(FleetType::class, 'key_fleet_join');
    }

    public function users()
    {
        return $this->belongsToMany(KeyType::class, 'user_key_joins');
    }
}
