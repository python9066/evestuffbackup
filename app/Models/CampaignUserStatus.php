<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUserStatus extends Model
{
    protected $guarded =[];
    public $timestamps = false;


    public function campaignusers()
    {
        return $this->hasMany(CampaignUser::class);
    }
}
