<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NodeJoin extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }


    public function campaignSystem()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function campaignSystemMulti()
    {
        return $this->belongsTo(CampaignSystem::class, 'custom_campaign_id', ' custom_campaign_id');
    }

    public function campaignUser()
    {
        return $this->belongsTo(CampaignUser::class);
    }

    public function campaignStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class);
    }
}
