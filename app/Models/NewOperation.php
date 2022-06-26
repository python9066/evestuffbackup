<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class NewOperation extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function campaign()
    {
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_operations', 'operation_id', 'campaign_id');
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'operation_user_lists', 'operation_id', 'user_id');
    }
}
