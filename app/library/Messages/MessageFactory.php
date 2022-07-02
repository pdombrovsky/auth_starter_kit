<?php

namespace Library\Messages;

use Phalcon\Messages\Message;

class MessageFactory
{
    
    public static function createMessage(int $code, string $detail) : Message
    {
        $message = new Message($detail);
        $message->setCode($code);
        return $message;
    }
   
}
