<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampagainStatus extends Model
{
    protected $guarded =[];

    public function campagain()
    {
        return $this->hasMany(Campaign::class);
    }

}
