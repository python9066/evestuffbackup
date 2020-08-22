<?php

namespace App;

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

}
