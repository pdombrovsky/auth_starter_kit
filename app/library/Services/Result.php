<?php

namespace Library\Services;

use Phalcon\Messages\Messages;

class Result
{
    const SUCCESS           = 1;
    const VALIDATION_ERRORS = 2;
    const ERRORS            = 3;

    private int $status;
    private ?Messages $messages;

    public function __construct(int $status = self::SUCCESS, ?Messages $messages = null)
    {
        $this->status = $status;
        $this->messages = $messages;
    }

    public function getMessages() : Messages
    {
        return $this->messages;
    }

    public function getStatus() : int
    {
        return $this->status;
    }
}
