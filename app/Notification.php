<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded =[];


   public function system()
   {
       return $this->belongsTo(System::class);
   }

   public function item()
   {
       return $this->belongsTo(Item::class);
   }

   public function type()
   {
       return $this->belongsTo(Type::class);
   }

   public function status()
   {
       return $this->belongsTo(Status::class);
   }
}
