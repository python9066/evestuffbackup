<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AmmoRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['*']);
    //     // Chain fluent methods for configuration options
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
