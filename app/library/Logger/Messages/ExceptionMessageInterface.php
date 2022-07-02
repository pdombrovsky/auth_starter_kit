<?php

namespace Library\Logger\Messages;

use Library\Logger\Messages\ItemsInterface;

use Throwable;

interface ExceptionMessageInterface extends ItemsInterface
{
    function setException(Throwable $exception);
}
