<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CampaignUserRecords extends Model
{
    use HasFactory;



    protected $casts = [
        'id' => 'integer',
        'site_id' => 'integer',
        'campaign_id' => 'integer',
        'campaign_system_id' => 'integer',
        'link' => 'integer',
        'system_id' => 'integer',
        'status_id' => 'integer',
        'role_id' => 'integer',
    ];
}
