<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCampaign extends Model
{
    use HasFactory;

    public function constellation()
    {
        return $this->belongsTo(Constellation::class);
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
