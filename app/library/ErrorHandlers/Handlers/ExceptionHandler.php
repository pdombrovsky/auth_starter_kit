<?php

namespace Library\ErrorHandlers\Handlers;

use Library\ErrorHandlers\HandlerInterface;

class ExceptionHandler
{
    public static function register(HandlerInterface $handler)
    {
        set_exception_handler([$handler, 'catchException']);
    }
}
