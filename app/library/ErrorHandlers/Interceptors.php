<?php

namespace Library\ErrorHandlers;

use Library\ErrorHandlers\HandlerInterface;
use Throwable;


class Interceptors implements HandlerInterface
{
    private array $interceptors;

    public function __construct()
    {
        $this->interceptors = [];
    }

    public function add(HandlerInterface $interceptor)
    {
        $this->interceptors[] = $interceptor;
    }

    public function catchException(Throwable $exception)
    {
        foreach($this->interceptors as $interceptor) {
            $interceptor->catchException($exception);
        }
    }
}
