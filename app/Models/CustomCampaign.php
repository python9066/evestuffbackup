<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomCampaign extends Model
{
    use HasFactory;
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
}
