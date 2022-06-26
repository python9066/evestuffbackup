<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function status()
    {
        return $this->hasOne(OperationInfoStatus::class, 'id', 'status');
    }
}
