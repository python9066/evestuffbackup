<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NodeJoin extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function campaignSystem()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function campaignUser()
    {
        return $this->belongsTo(CampaignUser::class);
    }

    public function status()
    {
        return $this->belongsTo(CampaignSystemStatus::class);
    }
}
