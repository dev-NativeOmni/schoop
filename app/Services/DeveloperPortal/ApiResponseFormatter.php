<?php

namespace App\Services\DeveloperPortal;

use Illuminate\Http\JsonResponse;

class ApiResponseFormatter
{
    public function ok(array $data = [], string $message = 'OK', array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    public function error(string $message, int $status = 400, array $errors = [], array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => $meta,
        ], $status);
    }
}
