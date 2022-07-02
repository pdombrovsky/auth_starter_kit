<?php

namespace Library\Logger\Messages;

use Library\Logger\Messages\MessageInterface;
use Library\Logger\Messages\InvalidArgumentException;
use Phalcon\Logger\Item;

class MessageItems implements MessageInterface
{
    private array $messages;
    private string $description;
    private bool $emptyLine;

    public function setMessages(array $messages)
    {
        if (!count($messages)) {
            throw new InvalidArgumentException('Array messages must contain at least one element.');
        }
        $this->messages = $messages;
        
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setEmptyLine(bool $emptyLine)
    {
        $this->emptyLine = $emptyLine;
    }

    public function getItems() : array
    {
        $items = [];
        foreach ($this->messages as $message) {
            $message = self::toString($message);
            $items[] = new Item($message, $this->description, 0);
        }
        
        if ($this->emptyLine) {

            $items[] = new Item(PHP_EOL, '', 0);
        }
        
        return $items;
    }
    private static function toString($message) : string
    {
        if (\is_array($message) || \is_object($message)) {
            return ':' . PHP_EOL . json_encode($message, JSON_PRETTY_PRINT);
        }
        return $message;
    }
}
