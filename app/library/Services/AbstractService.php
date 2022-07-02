<?php

declare(strict_types=1);

namespace Library\Services;

use Libary\Services\ServiceDomainException;
use Libary\Services\ServiceRuntimeException;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Messages\Messages;
use ReflectionClass;

abstract class AbstractService implements EventsAwareInterface
{
    protected ManagerInterface $eventsManager;

    public function __construct(?ManagerInterface $eventsManager = null)
    {
        $this->eventsManager = $eventsManager;
    }

    public function getEventsManager() : ?ManagerInterface
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager) : void
    {
        $this->eventsManager = $eventsManager;
    }

    protected function fireBeforeValidationExceptionThrown(Messages $messages, bool $fromChild = false)
    {
        return $this->fire('beforeValidationExceptionThrown', $messages, $fromChild);
    }

    protected function fireBeforeServiceExceptionThrown(ServiceDomainException $exception, bool $fromChild = false)
    {
        return $this->fire('beforeServiceExceptionThrown', $exception, $fromChild);
    }

    protected function fireBeforeServiceRuntimeExceptionThrown(ServiceRuntimeException $exception, bool $fromChild = false)
    {
        return $this->fire('beforeServiceRuntimeExceptionThrown', $exception, $fromChild);
    }

    protected function fire(string $eventName, $data, bool $fromChild, bool $cancelable = true)
    {
        if ($this->eventsManager) {

            $childService = ($fromChild) ? lcfirst(self::getChildName()) : 'abstractService';

            return $this->eventsManager->fire("{$childService}:{$eventName}", $this, $data, $cancelable);
        }
    }

    private static function getChildName() : string
    {
        $reflect = new ReflectionClass(static::class);
        return $reflect->getShortName();
    }
}
