<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded =[];

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
