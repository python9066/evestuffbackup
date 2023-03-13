<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dscan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(DscanItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function totals()
    {
        return $this->hasOne(DscanTotal::class);
    }
}
