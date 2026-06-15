<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class MobileApiResponse
{
    public static function ok(array $data = [], string $message = 'OK', array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    public static function error(string $message, array $errors = [], int $status = 400, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => $meta,
        ], $status);
    }
}
