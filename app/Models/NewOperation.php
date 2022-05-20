<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class NewOperation extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function campaign()
    {
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_operations', 'operation_id', 'campaign_id');
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }
}
