<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignSystemStatus extends Model
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


    public function campaignsystems()
    {
        return $this->hasMany(CampaignSystem::class);
    }

    public function nodeJoin()
    {
        return $this->hasMany(NodeJoin::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
