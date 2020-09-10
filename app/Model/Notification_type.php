<?php

namespace Model\App;

use Illuminate\Database\Eloquent\Model;

class Notification_type extends Model
{
    protected $guarded =[];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
