<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCampaign extends Model
{
    protected $guarded = [];
    public $timestamps = false;


    public function campaignjohn()
    {
        return $this->hasMany(CampaignJoin::class);
    }

    public function status()
    {
        return $this->belongsTo(CampaignStatus::class);
    }


    protected $casts = [
        'id' => 'integer'
    ];
}
