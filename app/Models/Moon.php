<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Moon extends Model
{
    use HasFactory, LogsActivity;
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

    public function constellation()
    {
        return $this->belongsTo(Constellation::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
