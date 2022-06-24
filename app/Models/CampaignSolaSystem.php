<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignSolaSystem extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    protected $guarded = [];
    public $timestamps = false;

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'system_id' => 'integer',
        'campaign_id' => 'integer',
        'tidi' => 'integer'
    ];
}
