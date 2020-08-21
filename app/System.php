<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $guarded =[];

    public function region()
    {
        return $this->belongsTO(Region::class);
    }

    public function constellation()
    {
        return $this->belongsTO(Constellation::class);
    }

}
