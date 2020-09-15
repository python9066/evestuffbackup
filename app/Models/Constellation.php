<?php

namespace App\Models;

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

    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
    ];


}
