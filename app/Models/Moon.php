<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moon extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function constellation()
    {
        return $this->belongsTo(Constellation::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
