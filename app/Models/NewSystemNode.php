<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewSystemNode extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }


    public function allUsers()
    {
        return $this->hasMany(NewUserNode::class, 'node_id');
    }


    public function nonePrimeNodeUser()
    {
        return $this->allUsers()->where('primery', 0);
    }

    public function primeNodeUser()
    {
        return $this->allUsers()->where('primery', 1);
    }

    public function nodeStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class, 'node_status', 'id');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
