<?php

namespace App\Repositories;

use App\Models\GroundRegistry;
use App\Models\Municipality;
use Illuminate\Support\Collection;

class GroundRegistryRepository
{
    public function getByMunicipality(Municipality $municipality, array $filters = []): Collection|array
    {
        $query = GroundRegistry::where('municipality_id', $municipality->id);

        $query->when($filters['zip_code'] ?? false, function ($query, $zipCode) {
            $query->where('zip_code', $zipCode);
        });

        $query->when($filters['cve_vus'] ?? false, function ($query, $cveVus) {
            $query->where('cve_vus', 'like', $cveVus . '%');
        });

        return $query->get();

    }

}