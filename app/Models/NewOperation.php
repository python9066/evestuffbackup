<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NewOperation extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_operations', 'operation_id', 'campaign_id');
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }
}
