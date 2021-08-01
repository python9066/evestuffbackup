<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconTaskSystems extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function reconTask()
    {
        return $this->belongsTo(ReconTasks::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
