<?php

namespace App\Services;

use App\Models\Municipality;
use App\Repositories\GroundRegistryRepository;
use App\Repositories\MunicipalityRepository;
use App\Services\Evaluation\PriceUnitEvaluation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroundRegistryService extends BaseService
{
    public function __construct(
        protected GroundRegistryRepository $groundRegistryRepository,
        protected MunicipalityRepository $municipalityRepository
    ) {
        parent::__construct();
    }


    public function getGroundPriceM2(array $data): array
    {
        $result = [];
        $validationRules = [
            'zip_code' => 'required|integer',
            'type' => 'required|string|in:min,max,avg',
            'cve_vus' => 'required|string|in:A,C,E',
        ];

        $validator = Validator::make($data, $validationRules);
        if ($validator->fails()) {
            $this->mergeErrors($validator->errors());
        }

        if (!$this->hasErrors()) {
            // TODO: get municipality by request
            $municipality = $this->municipalityRepository->getByName(Municipality::ALVARO_OBREGON_MUNICIPALITY);
            $groundRegistries = collect([]);
            if (isset($municipality)) {
                $groundRegistries = $this->groundRegistryRepository->getByMunicipality($municipality, $data);
            }
            $result = $this->getPriceUnitResult($data['type'], $groundRegistries);
        }
        return $result;
    }

    public function getPriceUnitResult(string $type, Collection $groundRegistries)
    {
        $result = [
            'type' => $type,
            'price_unit' => 0,
            'price_unit_construction' => 0
        ];
        list($priceUnitResult, $priceUnitConstructionResult) = $this->getPriceUnitEvals($groundRegistries);
        $result['elements'] = $priceUnitResult->getCount();
        $result['price_unit'] = $priceUnitResult->evaluate($type);
        $result['price_unit_construction'] = $priceUnitConstructionResult->evaluate($type);
        return $result;
    }


    public function getPriceUnitEvals(Collection $groundRegistries): array
    {
        $priceUnitEval = new PriceUnitEvaluation();
        $priceUnitConstructionEval = new PriceUnitEvaluation();

        $groundRegistries->each(function ($groundRegistry) use (&$priceUnitEval, &$priceUnitConstructionEval) {
            if ($groundRegistry->ground_surface > 0 && $groundRegistry->construction_surface > 0) {
                $priceUnit = ((float) $groundRegistry->ground_value / (float) $groundRegistry->ground_surface) - (float) $groundRegistry->subsidy;
                $priceUnitEval->addPriceUnit($priceUnit);
                $priceUnitConstruction = ((float) $groundRegistry->ground_value / (float) $groundRegistry->construction_surface) - (float) $groundRegistry->subsidy;
                $priceUnitConstructionEval->addPriceUnit($priceUnitConstruction);
            } else {
                Log::warning("The groud surface for ground registry id $groundRegistry->id/$groundRegistry->street_number is empty");
            }
        });
        return [$priceUnitEval, $priceUnitConstructionEval];
    }
}