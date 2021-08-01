<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconTasks extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function madeBy()
    {
        return $this->belongsTo(User::class, 'made_by_user_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }

    public function taskSystems()
    {
        return $this->hasMany(ReconTaskSystems::class);
    }

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'id' => 'integer',
        'made_by_user_id' => 'integer',
        'edited_by_user_id' => 'integer'
    ];
}
