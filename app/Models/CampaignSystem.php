<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSystem extends Model
{

    protected $guarded =[];

    public function campaignstatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignuser()
    {
        return $this->hasMany(CampaignUser::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }


}
