<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Constellation extends Model
{
    protected $guarded =[];

        public function region()
    {
        return $this->belongsTO(Region::class);
    }


    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }


}
