<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSystemNode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function nonePrimeUser()
    {
        return $this->hasMany(NewUserNode::class, 'node_id')->ofMany('primery', 0);
    }

    public function primeNodeUser()
    {
        return $this->hasOne(NewUserNode::class, 'node_id')->ofMany('primery', 1);
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
