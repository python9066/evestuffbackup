<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StartCampaignSystemRecords extends Model
{
    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $casts = [
        'constellation_id' => 'integer',
        'id' => 'integer',
        'start_campaign_id' => 'integer',
        'site_id' => 'integer',
        'start_campaign_id' => 'integer',
        'status_id' => 'integer',
        'system_id' => 'integer',
        'user_id' => 'integer'
    ];
}
