<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebWay extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
