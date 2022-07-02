<?php

declare(strict_types=1);

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Messages\Messages;

use Library\Services\AbstractService;
use Library\Http\Constants\HttpConstants;

class ValidationFailedListener extends Injectable
{
    public function beforeValidationExceptionThrown(Event $event, AbstractService $service, Messages $messages)
    {
        $this->response->setErrorsContent(HttpConstants::BAD_REQUEST, $messages);
        
        return false;
    }

}
