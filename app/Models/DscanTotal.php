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
    "old": {},
    "new": {}
  },
  "items": {
    "old": {},
    "new": {}
  },
  "categories": {
    "old": {},
    "new": {}
  },
  "chars": {
    "old": {},
    "new": {}
  },
  "corps": {
    "old": {},
    "new": {}
  },
  "alliances": {
    "old": {},
    "new": {}
  }
}'
    ];
}
