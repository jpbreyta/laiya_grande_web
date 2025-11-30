<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ErrorHelper
{
    /**
     * Throw a custom error with a specific status code and message
     * 
     * @param int $code HTTP status code
     * @param string|null $message Custom error message
     * @return void
     */
    public static function throw(int $code, ?string $message = null): void
    {
        abort($code, $message ?? self::getDefaultMessage($code));
    }

    /**
     * Get default error message for a given status code
     * 
     * @param int $code HTTP status code
     * @return string
     */
    private static function getDefaultMessage(int $code): string
    {
        return match($code) {
            401 => 'Unauthorized access',
            403 => 'Access forbidden',
            404 => 'Resource not found',
            419 => 'Page expired',
            429 => 'Too many requests',
            500 => 'Internal server error',
            503 => 'Service unavailable',
            default => 'An error occurred',
        };
    }

    /**
     * Throw 404 error
     * 
     * @param string|null $message
     * @return void
     */
    public static function notFound(?string $message = null): void
    {
        self::throw(404, $message);
    }

    /**
     * Throw 403 error
     * 
     * @param string|null $message
     * @return void
     */
    public static function forbidden(?string $message = null): void
    {
        self::throw(403, $message);
    }

    /**
     * Throw 401 error
     * 
     * @param string|null $message
     * @return void
     */
    public static function unauthorized(?string $message = null): void
    {
        self::throw(401, $message);
    }

    /**
     * Throw 500 error
     * 
     * @param string|null $message
     * @return void
     */
    public static function serverError(?string $message = null): void
    {
        self::throw(500, $message);
    }
}
