<?php

namespace App\Repositories;
use App\Models\Municipality;

class MunicipalityRepository
{
    public function getByName(string $name): ?Municipality
    {
        return Municipality::where('name', $name)->first();
    }
}