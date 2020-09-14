<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUser extends Model
{
    use HasFactory;
    protected $guarded =[];
    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaignsystem()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function status()
    {
        return $this->belongsTo(CampaignUserStatus::class);
    }

    public function role()
    {
        return $this->belongsTo(CampaignUserRole::class);
    }


}
