<?php

namespace Library\Logger\Messages;

use Library\Logger\Messages\ItemsInterface;

interface MessageInterface extends ItemsInterface
{
    function setMessages(array $messages);
    function setDescription(string $description);
    function setEmptyLine(bool $emptyLine);
}
