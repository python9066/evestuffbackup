<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignStatus extends Model
{
    protected $guarded =[];

    public function campagain()
    {
        return $this->hasMany(Campaign::class);
    }

}
