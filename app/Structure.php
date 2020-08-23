<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $guarded =[];



    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
