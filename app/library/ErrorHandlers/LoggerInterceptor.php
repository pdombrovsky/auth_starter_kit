<?php

namespace Library\ErrorHandlers;

use Library\ErrorHandlers\HandlerInterface;
use Library\Logger\ErrorLoggerInterface;
use Throwable;


class LoggerInterceptor implements HandlerInterface
{
    private ErrorLoggerInterface $logger;

    public function __construct(ErrorLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function catchException(Throwable $exception)
    {
        $this->logger->save($exception);
    }
}
