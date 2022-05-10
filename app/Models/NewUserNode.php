<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewUserNode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function opUser()
    {
        return $this->belongsTo(OperationUser::class, 'operation_user_id', 'id');
    }

    public function node()
    {
        return $this->belongsTo(NewSystemNode::class, 'node_id', 'id');
    }


    public function nodeStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class, 'node_status_id', 'id');
    }
}
