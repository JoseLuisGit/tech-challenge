<?php
namespace App\Http\Controllers\Api;

use App\Trait\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\GroundRegistryService;
use Illuminate\Http\Request;

class GroundRegistryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected GroundRegistryService $groundRegistryService)
    {
    }
    public function getGroundPriceM2(Request $request, string $zipCode, string $type)
    {
        $requestAll = array_merge($request->all(), ['zip_code' => $zipCode, 'type' => $type]);
        $data = $this->groundRegistryService->getGroundPriceM2($requestAll);

        if ($this->groundRegistryService->hasErrors()) {
            return $this->sendResponse(['errors' => $this->groundRegistryService->getErrors()], 422);
        }
        return $this->sendResponse($data);
    }
}
