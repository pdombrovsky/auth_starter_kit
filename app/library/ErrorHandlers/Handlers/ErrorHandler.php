<?php

namespace Library\ErrorHandlers\Handlers;

use ErrorException;

class ErrorHandler
{
    public static function register()
    {
        set_error_handler([__CLASS__, 'handleError']);
    }

    public static function handleError(int $errno, string $errstr, ?string $errfile, ?int $errline)
    {
        $exception = new ErrorException($errstr, -1, $errno, $errfile, $errline);
        throw $exception;
    }
}
