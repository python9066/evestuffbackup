<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded =[];

    public function system()
    {
        return $this->hasMany(System::class);
    }

    public function constellation()
    {
        return $this->hasMany(constellation::class);
    }
}
