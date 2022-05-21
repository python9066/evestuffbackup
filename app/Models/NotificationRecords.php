<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationRecords extends Model
{
    use LogsActivity;
    protected $guarded = [];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'region_id' => 'integer',
        'item_id' => 'integer',
        'adm' => 'double',
        'notification_type_id' => 'integer',
        'status_id' => 'integer',
    ];
}
