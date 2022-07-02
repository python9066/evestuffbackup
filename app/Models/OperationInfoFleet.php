<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OperationInfoFleet extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user associated with the OperationInfoFleet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fc(): HasOne
    {
        return $this->hasOne(OperationInfoUser::class, 'id', 'fc_id');
    }

    public function boss(): HasOne
    {
        return $this->hasOne(OperationInfoUser::class, 'id', 'boss_id');
    }

    public function mumble(): HasOne
    {
        return $this->hasOne(OperationInfoMumble::class, 'id', 'mumble_id');
    }

    public function doctrine(): HasOne
    {
        return $this->hasOne(OperationInfoDoctrine::class, 'id', 'doctrine_id');
    }

    public function alliance(): HasOne
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_id');
    }

    /**
     * Get the recon associated with the OperationInfoFleet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recon(): HasOne
    {
        return $this->hasOne(OperationInfoRecon::class, 'id', 'recon_id');
    }
}
