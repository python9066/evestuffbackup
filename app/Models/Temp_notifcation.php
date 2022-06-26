<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Temp_notifcation extends Model
{

    protected $guarded = [];



    protected $casts = [
        'id' => 'integer',
        'event_type_id' => 'integer',
        'system_id' => 'integer',
        'notification_type_id' => 'integer',
        'es_id' => 'integer',
        'status' => 'integer',
    ];
}
