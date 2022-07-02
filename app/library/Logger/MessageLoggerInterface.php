<?php

namespace Library\Logger;

use Library\Logger\Messages\ItemsInterface;

interface MessageLoggerInterface
{
    public function save(array $messages, string $description = ItemsInterface::__INFO__, bool $emptyLine = true) : void;
}
