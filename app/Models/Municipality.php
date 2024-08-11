<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';

    public function ground_registries()
    {
        return $this->hasMany(GroundRegistry::class, 'municipality_id', 'id');
    }
}
