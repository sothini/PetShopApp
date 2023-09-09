<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Respond with a success JSON.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data, $statusCode = 200,$extra=[]): JsonResponse
    {
        return response()->json([
            'success' => 1,
            'data' => $data,
            'error' => null,
            'errors' => [],
            'extra' => $extra,
        ], $statusCode);
    }

    /**
     * Respond with an error JSON.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse($message, $statusCode,$errors=[],$extra=[]): JsonResponse
    {
        return response()->json([
            'success' => 0,
            'error' => $message,
            'errors' => $errors,
            'extra' => $extra,
        ], $statusCode);
    }
}
