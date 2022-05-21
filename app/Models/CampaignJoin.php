<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignJoin extends Model
{

    use HasFactory, LogsActivity;
    protected $guarded = [];
    public $timestamps = false;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function customcampaign()
    {
        return $this->belongsTo(CustomCampaign::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignrecords()
    {
        return $this->belongsTo(CampaignRecords::class, "id");
    }

    protected $casts = [
        'custom_campaign_id' => 'integer',
        'campaign_id' => 'integer',
    ];
}
