<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSystemNode extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function allUsers()
    {
        return $this->hasMany(NewUserNode::class, 'node_id');
    }


    public function nonePrimeNodeUser()
    {
        return $this->allUsers()->where('primery', 0);
    }

    public function primeNodeUser()
    {
        return $this->allUsers()->where('primery', 1);
    }

    public function nodeStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class, 'node_status', 'id');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function opUser()
    {
        return $this->belongsTo(OperationUser::class, 'operation_user_id', 'id');
    }
}
