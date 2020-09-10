<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $guarded =[];



    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function campaign()
    {
        return $this->hasOne(Campaign::class);
    }
}
