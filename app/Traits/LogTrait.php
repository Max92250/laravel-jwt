<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
trait LogTrait
{
    /**
     * Log a message and return a JSON response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function storeSuccessResponse($data): JsonResponse
    {
        return response()->json(['message' => 'Size created successfully', 'data' => $data], 201);
    }
}
