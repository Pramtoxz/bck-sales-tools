<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    /**
     * Success response (200)
     */
    public static function success($data = null, string $message = 'Success'): JsonResponse
    {
        Log::info('API Response Success', [
            'status' => 200,
            'message' => $message,
            'data' => $data
        ]);

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Bad Request (400)
     * Valid data was given but the request has failed
     */
    public static function badRequest(string $message = 'Valid data was given but the request has failed', $error = null): JsonResponse
    {
        Log::warning('API Response Bad Request', [
            'status' => 400,
            'message' => $message,
            'error' => $error
        ]);

        $response = [
            'status' => 400,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, 400);
    }

    /**
     * Unauthorized (401)
     * No valid API Key was given
     */
    public static function unauthorized(string $message = 'No valid API Key was given', $error = null): JsonResponse
    {
        Log::warning('API Response Unauthorized', [
            'status' => 401,
            'message' => $message,
            'error' => $error,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $response = [
            'status' => 401,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, 401);
    }

    /**
     * Not Found (404)
     * The request resource could not be found
     */
    public static function notFound(string $message = 'The request resource could not be found', $error = null): JsonResponse
    {
        Log::warning('API Response Not Found', [
            'status' => 404,
            'message' => $message,
            'error' => $error,
            'url' => request()->fullUrl()
        ]);

        $response = [
            'status' => 404,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, 404);
    }

    /**
     * Unprocessable Entity (422)
     * The payload has missing required parameters or invalid data was given
     */
    public static function validationError(string $message = 'The payload has missing required parameters or invalid data was given', $errors = null): JsonResponse
    {
        Log::warning('API Response Validation Error', [
            'status' => 422,
            'message' => $message,
            'errors' => $errors,
            'request_data' => request()->except(['password', 'api_token', 'Api-Token'])
        ]);

        $response = [
            'status' => 422,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, 422);
    }

    /**
     * Internal Server Error (500)
     * Request failed due to an internal error
     */
    public static function serverError(string $message = 'Request failed due to an internal error', $error = null): JsonResponse
    {
        Log::error('API Response Server Error', [
            'status' => 500,
            'message' => $message,
            'error' => $error,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip()
        ]);

        $response = [
            'status' => 500,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, 500);
    }

    /**
     * Service Unavailable (503)
     * Service is offline for maintenance
     */
    public static function serviceUnavailable(string $message = 'Service is offline for maintenance', $error = null): JsonResponse
    {
        Log::error('API Response Service Unavailable', [
            'status' => 503,
            'message' => $message,
            'error' => $error
        ]);

        $response = [
            'status' => 503,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, 503);
    }

    /**
     * Custom response dengan status code dinamis
     */
    public static function custom(int $statusCode, string $message, $data = null, $error = null): JsonResponse
    {
        $logLevel = $statusCode >= 500 ? 'error' : ($statusCode >= 400 ? 'warning' : 'info');
        
        Log::log($logLevel, 'API Response Custom', [
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
            'error' => $error
        ]);

        $response = [
            'status' => $statusCode,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($error !== null) {
            $response['error'] = $error;
        }

        return response()->json($response, $statusCode);
    }
}