<?php

namespace Library\ErrorHandlers\Handlers;

use ErrorException;
use Library\ErrorHandlers\HandlerInterface;

class ShutdownHandler
{
    private static HandlerInterface $handler;
    
    public static function register(HandlerInterface $handler)
    {
        self::$handler = $handler;
        register_shutdown_function([__CLASS__, 'handle']);
    }

    public static function handle()
    {
        $error = error_get_last();
        if (empty($error)) {
            return;
        }
        $exception = new ErrorException($error['message'], -2, $error['type'], $error['file'], $error['line']);
        self::$handler->catchException($exception);
    }
}
