<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Library\Http\Exceptions\HttpException;
use Library\Http\Constants\HttpErrors;

class AuthorizationListener extends Injectable
{
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        if (!$this->router->wasMatched()) {
            return true;
        }

        $whiteList = $this->config->whiteList;
        $action = $dispatcher->getActionName();

        if (\in_array($action, $whiteList->toArray())) {
            return true;
        }

        if (!$this->request->hasHeader('Authorization')) {

            throw new HttpException('The Authorization header is missing', HttpErrors::AUTH_HEADER_REQUIRED);
        }

        $header = $this->request->getHeader('Authorization');

        if (strpos($header, 'Bearer ') !== 0) {

            throw new HttpException('Bearer is missing', HttpErrors::BEARER_REQUIRED);
        }

        $token = self::extractToken($header);

        $result = $this->authorizationService->authorize($token);
        
        return $result;
    }

    private static function extractToken(string $header) : string
    {
        $token = str_replace('Bearer', '', $header);
        return str_replace(' ', '', $token);
    }
}