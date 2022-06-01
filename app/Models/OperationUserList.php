<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationUserList extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function opUsers()
    {
        return $this->belongsTo(OperationUser::class, 'operation_id', 'id');
    }
}
