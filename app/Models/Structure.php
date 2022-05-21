<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Structure extends Model
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

    public function campaign()
    {
        return $this->hasOne(Campaign::class);
    }

    protected $casts = [
        'id' => 'integer',
        'alliance_id' => 'integer',
        'system_id' => 'integer',
        'item_id' => 'integer',
        'adm' => 'double',
        'status' => 'integer',
    ];
}
