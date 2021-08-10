<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcStationRecords extends Model
{
    use HasFactory;


    protected $casts = [
        'alliance_id' => 'integer',
        'fc_user_id' => 'integer',
        'item_id' => 'integer',
        'rc_fc_id' => 'integer',
        'rc_recon_id' => 'integer',
        'recon_user_id' => 'integer',
        'status_id' => 'integer',
        'system_id' => 'integer',
        'out' => 'integer',

    ];
}
