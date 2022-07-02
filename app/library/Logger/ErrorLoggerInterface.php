<?php

namespace Library\Logger;

use Throwable;

interface ErrorLoggerInterface
{
    public function save(Throwable $exception) : void;
}
