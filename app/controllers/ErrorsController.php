<?php

namespace App\Controllers;

use Library\Messages\MessageFactory;
use Library\Http\Constants\HttpErrors;
use Library\Http\Constants\HttpConstants;

use Library\Services\Exceptions\ValidationException;

use Phalcon\Mvc\Controller;

use Throwable;

class ErrorsController extends Controller
{
    public function notFoundAction()
    {
        $message = MessageFactory::createMessage(HttpErrors::NOT_FOUND, 'This route not exists');
        $this->response->setErrorsContent(HttpConstants::NOT_FOUND, [$message]);
    }

    public function ValidationExceptionAction(ValidationException $exception)
    {
        $messages = $exception->getValidationMessages();
        $this->response->setErrorsContent(HttpConstants::BAD_REQUEST, $messages);
    }

    public function ExceptionAction(Throwable $exception)
    {
        $this->response->setError($exception);
    }
}
