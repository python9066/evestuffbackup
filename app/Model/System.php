<?php

namespace Model\App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $guarded =[];

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

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

}
