<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Helpers\ErrorHelper;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, string>
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) { // Handle JSON requests
            $statusCode = 500;
            $message = 'An unexpected error occurred.';
            $errors = [];

            if ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
                $message = $e->getMessage() ?: 'An error occurred.';
            } elseif ($e instanceof QueryException) { // Database error
                $statusCode = 500;
                $message = 'Database error occurred.';
                if (config('app.debug')) {
                    $errors = [$e->getMessage()]; // Show detailed error in debug mode
                }
            } else {
                if (config('app.debug')) {
                    $message = $e->getMessage(); // Show detailed error in debug mode
                    // $errors = [$e]; // Or the whole exception for debugging
                }
            }

            // return ErrorHelper::formatErrorResponse($message, $errors, $statusCode);
        }

        return parent::render($request, $e); // Default rendering for non-JSON requests
    }
}
