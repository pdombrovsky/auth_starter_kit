<?php

namespace Library\Tokens\Builders;

use Library\Tokens\Token;

use DateTimeInterface;

abstract class AbstractBuilder
{
    protected string $lifetime;
    protected string $issuer;
    protected DateTimeInterface $now;
    protected string $uuid;

    public function __construct(string $issuer, string $lifetime)
    {
        $this->issuer = $issuer;
        $this->lifetime = $lifetime;
    }

    public function setNow(DateTimeInterface $now) : AbstractBuilder
    {
        $this->now = $now;
        return $this;
    }

    public function setUuid(string $uuid) : AbstractBuilder
    {
        $this->uuid = $uuid;
        return $this;
    }

    public abstract function getToken(): Token;
   
}
