<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcFcUsers extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];
}
