<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundRegistry extends Model
{
    use HasFactory;
    protected $table = 'ground_registries';

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }
}
