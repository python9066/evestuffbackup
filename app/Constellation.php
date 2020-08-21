<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constellation extends Model
{
    protected $guarded =[];

    public function system()
    {
        return $this->hasMany(System::class);
    }

    public function region()
    {
        return $this->belongsTO(Region::class);
    }
}
