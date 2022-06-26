<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FeedBack extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
