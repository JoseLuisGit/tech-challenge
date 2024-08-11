<?php

namespace App\Services;
use App\Repositories\GroundRegistryRepository;
use App\Repositories\MunicipalityRepository;

class GroundRegistryService
{
    public function __construct(
        protected GroundRegistryRepository $groundRegistryRepository,
        protected MunicipalityRepository $municipalityRepository
    )
    {
    }
}