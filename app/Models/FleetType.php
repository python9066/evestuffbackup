<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetType extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function keys()
    {
        return $this->belongsToMany(KeyType::class, 'key_fleet_join');
    }
}
