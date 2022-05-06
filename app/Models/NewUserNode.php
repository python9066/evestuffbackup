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
}
