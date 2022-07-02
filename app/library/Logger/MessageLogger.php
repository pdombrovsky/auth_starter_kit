<?php

namespace Library\Logger;

use Phalcon\Logger\Adapter\AdapterInterface;
use Library\Logger\Messages\MessageInterface;
use Library\Logger\Messages\ItemsInterface;
use Library\Logger\MessageLoggerInterface;
use Library\Logger\Logger;

class MessageLogger extends Logger implements MessageLoggerInterface
{
    public function __construct(AdapterInterface $adapter, MessageInterface $messageCreator)
    {
        $this->messageCreator = $messageCreator;
        parent::__construct($adapter);
    }
    public function save(array $messages, string $description = ItemsInterface::__INFO__, bool $emptyLine = true) : void
    {
        $this->messageCreator->setEmptyLine($emptyLine);
        $this->messageCreator->setDescription($description);
        $this->messageCreator->setMessages($messages, $description);
        $this->log($this->messageCreator);
    }
}
