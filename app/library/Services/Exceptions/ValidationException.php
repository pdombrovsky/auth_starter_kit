<?php

namespace Library\Services\Exceptions;

use Phalcon\Messages\Messages;

class ValidationException extends \Exception
{
    protected Messages $messages;

    public function __construct(Messages $validationMessages, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->messages = $validationMessages;
    }

    public function getValidationMessages()
    {
        return $this->messages;
    }
}
