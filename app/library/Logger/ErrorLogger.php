<?php

namespace Library\Logger;

use Library\Logger\Messages\ExceptionMessageInterface;
use Library\Logger\ErrorLoggerInterface;
use Phalcon\Logger\Adapter\AdapterInterface;
use Library\Logger\Logger;

use Throwable;

class ErrorLogger extends Logger implements ErrorLoggerInterface
{
    private ExceptionMessageInterface $messageCreator;

    public function __construct(AdapterInterface $adapter, ExceptionMessageInterface $messageCreator)
    {
        $this->messageCreator = $messageCreator;
        parent::__construct($adapter);
    }
    
    public function save(Throwable $exception) : void
    {
        $this->messageCreator->setException($exception);
        $this->log($this->messageCreator);
    }
}
