<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignJoin extends Model
{
    protected $guarded =[];
    public $timestamps = false;

    public function customcampaign()
    {
        return $this->belongsTo(CustomCampaign::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignrecords()
    {
        return $this->belongsTo(CampaignRecords::class,"campagin_id");
    }
}
