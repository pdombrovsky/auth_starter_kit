<?php

namespace Library\ErrorHandlers;

use Throwable;

interface HandlerInterface
{
    function catchException(Throwable $exception);
}
