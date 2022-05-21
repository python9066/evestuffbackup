<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewCampaignOperation extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*', 'campaign.name', 'operation.title']);
        // Chain fluent methods for configuration options
    }

    public function link()
    {
        return $this->hasMany(Logging::class);
    }

    public function operation()
    {
        return $this->belongsTo(NewOperation::class, 'operation_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(NewCampaign::class, 'campaign_id', 'id');
    }
}
