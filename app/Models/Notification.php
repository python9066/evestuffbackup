<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Notification extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }


    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function notification_type()
    {
        return $this->belongsTo(Notification_type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'item_id' => 'integer',
        'notification_type_id' => 'integer',
        'si_id' => 'integer',
    ];
}
