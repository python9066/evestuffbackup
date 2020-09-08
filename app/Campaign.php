<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];


    public function constellation()
    {
        return $this->belongsTO(Constellation::class);
    }

    public function alliance()
    {
        return $this->belongsTO(Alliance::class);
    }

    public function system()
    {
        return $this->belongsTO(System::class);
    }

    public function structure()
    {
        return $this->hasOne(Structure::class);
    }



}
