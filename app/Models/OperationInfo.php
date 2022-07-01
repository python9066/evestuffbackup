<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationInfo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function status()
    {
        return $this->hasOne(OperationInfoStatus::class, 'id', 'status_id');
    }

    /**
     * Get all of the comments for the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(OperationInfoMessage::class, 'operation_info_id', 'id')->orderBy('id', 'desc');
    }


    public function recons(): HasMany
    {
        return $this->hasMany(OperationInfoRecon::class, 'operation_info_id', 'id');
    }


    /**
     * Get all of the comments for the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fleets(): HasMany
    {
        return $this->hasMany(OperationInfoFleet::class, 'operation_info_id', 'id');
    }
}
