<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StartCampaignSystems extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $casts = [
        'id' => 'integer',
        'start_campaign_id' => 'integer',
        'system_id' => 'integer',
        'constellation_id' => 'integer',
        'campaign_user_id' => 'integer',
        'campaign_system_status_id' => 'integer',
        'base_time' => 'integer',

    ];
}
