<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSolaSystem extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function system()
    {
        return $this->belongsTo(System::class, 'system_id', 'system_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'system_id' => 'integer',
        'campaign_id' => 'integer',
        'tidi' => 'integer'
    ];
}
