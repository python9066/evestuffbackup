<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    protected $guarded =[];

    public function alliance_id()
    {
        return $this->hasMany(Post::class)->orderBy('created_at','DESC');
    }
    public $incrementing = false;
    protected $primaryKey = 'id';

}
