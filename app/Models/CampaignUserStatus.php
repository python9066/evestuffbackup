<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignUserStatus extends Model
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

    public function campaignusers()
    {
        return $this->hasMany(CampaignUser::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
