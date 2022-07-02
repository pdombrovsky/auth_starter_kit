<?php

namespace Library\Logger\Factories;

use Phalcon\Logger\Adapter\Stream;
use Library\Logger\Formatter\Line;
use Library\Logger\Messages\ExceptionMessageItems;
use Library\Logger\Messages\MessageItems;
use Library\Logger\MessageLogger;
use Library\Logger\ErrorLogger;
use Library\Logger\MessageLoggerInterface;
use Library\Logger\ErrorLoggerInterface;

class LoggerFactory
{
    public static function createErrorLogger(string $filename) : ErrorLoggerInterface
    {
        $stream = new Stream($filename);
        $stream->setFormatter(new Line());
        return new ErrorLogger($stream, new ExceptionMessageItems());
    }

    public static function createMessageLogger(string $filename) : MessageLoggerInterface
    {
        $stream = new Stream($filename);
        $stream->setFormatter(new Line());
        return new MessageLogger($stream, new MessageItems());
    }
}
