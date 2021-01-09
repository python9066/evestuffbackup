<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSolaSystem extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function systems()
    {
        return $this->belongsTo(System::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class);
    }

    public function supervier()
    {
        return $this->belongsTo(User::class);
    }
}
