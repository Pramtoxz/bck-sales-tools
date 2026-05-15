<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

class WhatsAppResponse
{
    /**
     * Format success response untuk WhatsApp
     */
    public static function success(array $data, ?string $uniqueId = null): array
    {
        Log::info('WhatsApp Response Success', [
            'status' => 200,
            'unique_id' => $uniqueId,
            'data' => $data
        ]);

        return [
            'success' => true,
            'status' => 200,
            'message' => 'Message sent successfully',
            'data' => $data,
            'unique_id' => $uniqueId
        ];
    }

    /**
     * Format error response berdasarkan status code
     */
    public static function error(int $statusCode, $error = null): array
    {
        $messages = [
            400 => 'Valid data was given but the request has failed',
            401 => 'No valid API Key was given',
            404 => 'The request resource could not be found',
            422 => 'The payload has missing required parameters or invalid data was given',
            500 => 'Request failed due to an internal error in Multichannel',
            503 => 'Multichannel is offline for maintenance'
        ];

        $message = $messages[$statusCode] ?? 'Unexpected response from server';

        Log::error('WhatsApp Response Error', [
            'status' => $statusCode,
            'message' => $message,
            'error' => $error
        ]);

        return [
            'success' => false,
            'status' => $statusCode,
            'message' => $message,
            'error' => $error
        ]; 
    }

    /**
     * Connection error response
     */
    public static function connectionError(string $errorMessage): array
    {
        Log::error('WhatsApp Connection Error', [
            'status' => 503,
            'error' => $errorMessage
        ]);

        return [
            'success' => false,
            'status' => 503,
            'message' => 'Connection timeout or network error',
            'error' => $errorMessage
        ];
    }

    /**
     * Unexpected error response
     */
    public static function unexpectedError(string $errorMessage): array
    {
        Log::error('WhatsApp Unexpected Error', [
            'status' => 500,
            'error' => $errorMessage,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)
        ]);

        return [
            'success' => false,
            'status' => 500,
            'message' => 'Unexpected error occurred',
            'error' => $errorMessage
        ];
    }
}