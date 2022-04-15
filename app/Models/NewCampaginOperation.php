<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCampaginOperation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function link()
    {
        return $this->hasMany(Logging::class);
    }
}
