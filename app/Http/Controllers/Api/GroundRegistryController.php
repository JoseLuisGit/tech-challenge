<?php
namespace App\Http\Controllers\Api;

use App\Trait\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\GroundRegistryService;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Ground Registry Price Unit", version="1.0")
 */
class GroundRegistryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected GroundRegistryService $groundRegistryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/price-m2/zip-codes/{zip_code}/aggregate/{type}",
     *     @OA\Parameter(
     *         name="zip_code",
     *         in="path",
     *         required=true,
     *         description="Zip Code",
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="Only min, max or avg",
     *      ),
     *     @OA\Parameter(
     *         name="cve_vus",
     *         in="query",
     *         required=true,
     *         description="Only A, C or E",
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=422, description="Invalid request")
     * )
     */
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
