<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignStatus extends Model
{

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;



    public function campagain()
    {
        return $this->hasMany(Campaign::class);
    }

    public function customcampagain()
    {
        return $this->hasMany(CustomCampaign::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
