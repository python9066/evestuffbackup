<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StartCampaigns extends Model
{
    use HasFactory;
    protected $guarded = [];



    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];
}
