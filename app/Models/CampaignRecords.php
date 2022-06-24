<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignRecords extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
        'constellation_id' => 'integer',
        'system_id' => 'integer',
        'alliance_id' => 'integer',
        'color' => 'integer',
        'attackers_score' => 'double',
        'defenders_score' => 'double',
        'notification_type_id' => 'integer',
        'status_id' => 'integer',
        'standing' => 'integer',
        'defenders_score_old' => 'double',
        'attackers_score_old' => 'double',
        'notification_type_id' => 'double',
    ];

    public function campaignjoin()
    {
        return $this->hasMany(CampaignJoin::class);
    }

    public function webway()
    {
        return $this->hasMany(WebWay::class, 'system_id', 'system_id');
    }

    public function priority()
    {
        return $this->hasOne(Campaign::class, 'id', 'id');
    }
}
