<?php

namespace Library\Logger\Messages;

use Library\Logger\Messages\ExceptionMessageInterface;
use Phalcon\Logger\Item;
use Throwable;

class ExceptionMessageItems implements ExceptionMessageInterface
{
    private Throwable $exception;

    public function setException(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getItems() : array
    {
        $errorItem = $this->getErrorItem();
        $infoItem = $this->getInfoItem();
        $debugItem = $this->getDebugItem();
        $emptyItem = $this->getEmptyItem();
        
        return [
            $errorItem,
            $infoItem,
            $debugItem,
            $emptyItem
        ];
    }

    private function getErrorItem() : Item
    {
        $message = get_class($this->exception) . '[' . $this->exception->getCode() . ']: ' . $this->exception->getMessage();
        return new Item($message, ExceptionMessageInterface::__ERROR__, 0);
    }

    private function getInfoItem() : Item
    {
        $message = $this->exception->getFile() . '[' . $this->exception->getLine() . ']';
        return new Item($message, ExceptionMessageInterface::__INFO__, 0);
    }

    private function getDebugItem() : Item
    {
        $message = "Trace: " . $this->exception->getTraceAsString();
        return new Item($message, ExceptionMessageInterface::__DEBUG__, 0);
    }

    private function getEmptyItem() : Item
    {
        return new Item(PHP_EOL, '', 0);
    }
}
