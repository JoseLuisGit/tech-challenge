<?php
namespace App\Trait\Api;

trait ApiResponseTrait
{
    public function sendResponse(array $data, int $statusCode = 200)
    {
        $response = [
            'status' => $statusCode === 200,
            'payload' => !array_key_exists('errors', $data) ? $data : [],
        ];
        if (array_key_exists('errors', $data)) {
            $response['errors'] = $data['errors'];
        }
        return response()->json($response, $statusCode);
    }
}