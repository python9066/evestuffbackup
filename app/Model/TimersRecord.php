<?php

namespace Model\App;

use Illuminate\Database\Eloquent\Model;

class TimersRecord extends Model
{
    protected $table = 'timer_records';
    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }
}
