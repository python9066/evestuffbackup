<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSystemNode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function opUsers()
    {
        return $this->hasMany(OperationUser::class);
    }
}
