<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationUser extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userrole()
    {
        return $this->belongsTo(CampaignUserRole::class, "role_id", "id");
    }

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'entosis' => 'integer',
        'operation_id' => 'integer',
        'user_status_id' => 'integer',
        'system_id' => 'integer',
        'role_id' => 'integer',

    ];
}
