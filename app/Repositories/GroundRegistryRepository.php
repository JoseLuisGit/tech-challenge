<?php

namespace App\Repositories;
use App\Models\GroundRegistry;
use App\Models\Municipality;


class GroundRegistryRepository
{
    public function getByMunicipality(Municipality $municipality, array $filters = []): ?GroundRegistry
    {
        // TODO: Use Filters
        return GroundRegistry::where('municipality_id', $municipality->id)->get();

    }

}