<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

use Library\Http\Exceptions\HttpException;
use Library\Services\Exceptions\ValidationException;
use Library\Services\Exceptions\ServiceException;

use Library\Messages\MessagesConverter;

use Throwable;

class LoggerListener extends Injectable
{
    public function beforeException(Event $event, Dispatcher $dispatcher, Throwable $exception)
    {
        try{

            throw $exception;

        } catch (ValidationException $exception) {

            $messages = $exception->getValidationMessages();
            $converted = MessagesConverter::convertCollectionForLog($messages);
            $this->accessLog($converted);
          
        } catch (ServiceException | HttpException $exception) {

            $this->accessLog(
                [
                    'Code: ' . $exception->getCode(),
                    'Message: ' . $exception->getMessage()
                ]
            );

        } catch (Throwable $exception) {

            $this->errorLogger->save($exception);
        }
    }

    private function accessLog(array $messages)
    {
        $this->messageLogger->save(
            $this->requestFrom(),
            'ACCESS', 
            false
        );
        $this->messageLogger->save(
            $messages,
            'ERRORS'
        );
    }

    private function requestFrom() : array
    {
        return [
            'Method: ' . $this->request->getMethod(),
            'From: ' . $this->request->getClientAddress(),
            'URi: ' . $this->request->getUri()
        ];
    }
}
