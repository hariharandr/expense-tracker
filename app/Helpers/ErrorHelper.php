<?php

namespace App\Helpers;

if (! function_exists('formatErrorResponse')) {
    function formatErrorResponse($message, $errors = [], $statusCode = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
