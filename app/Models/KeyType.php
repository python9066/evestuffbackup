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
        return $this->hasManyThrough(fleets::class, KeyFleetJoin::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserKeyJoin::class);
    }
}
