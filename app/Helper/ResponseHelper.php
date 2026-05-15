<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function jsonResponse($success, $message, $data, $statusCode): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
