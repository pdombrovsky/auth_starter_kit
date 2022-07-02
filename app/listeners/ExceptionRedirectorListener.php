<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

use Library\Http\Exceptions\HttpException;
use Library\Services\Exceptions\ServiceException;
use Library\Services\Exceptions\ValidationException;

use Throwable;
use Exception;

class ExceptionRedirectorListener extends Injectable
{
    public function beforeException(Event $event, Dispatcher $dispatcher, Throwable $exception)
    {
        try{

            throw $exception;

        } catch (ValidationException $exception) {

            $params = self::getParams('ValidationException', $exception);

        } catch (ServiceException | HttpException $exception) {

            $params = self::getParams('Exception', $exception);

        } catch (Throwable $exception) {

            $facadeException = new Exception('Sorry, this may be an error on the server side', $exception->getCode());
            $params = self::getParams('Exception', $facadeException);
        }

        $dispatcher->forward($params);
        return false;
    }

    private static function getParams(string $action, Throwable $exception)
    {
        return [
            'controller' => 'Errors',
            'action'     => $action,
            'params'     => [$exception]
        ];
    }
}
