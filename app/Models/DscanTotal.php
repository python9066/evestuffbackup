<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DscanTotal extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        "totals"

    ];

    protected $casts = [
        'totals' => 'array',

    ];

    protected $attributes = [
        'totals' => '{
  "groups": {
    "new": {},
    "same": {},
    "left":{},
  },
  "items": {
    "new": {},
    "same": {},
    "left":{},
  },
  "categories": {
    "new": {},
    "same": {},
    "left":{},
  },
  "chars": {
    "new": {},
    "same": {},
    "left":{},
  },
  "corps": {
    "new": {},
    "same": {},
    "left":{},
  },
  "alliances": {
    "new": {},
    "same": {},
    "left":{},
  }
}'
    ];
}
