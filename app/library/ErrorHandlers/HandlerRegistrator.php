<?php

namespace Library\ErrorHandlers;

use Library\ErrorHandlers\HandlerInterface;
use Library\ErrorHandlers\Handlers\ErrorHandler;
use Library\ErrorHandlers\Handlers\ExceptionHandler;
use Library\ErrorHandlers\Handlers\ShutdownHandler;


class HandlerRegistrator
{
    public static function register(HandlerInterface $interceptor)
    {
        ErrorHandler::register();
        ShutdownHandler::register($interceptor);
        //ExceptionHandler::register($interceptor);
    }

}
