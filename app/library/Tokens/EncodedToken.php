<?php

namespace Library\Tokens;

use DateTimeInterface;

class EncodedToken
{
    protected string $token;
    protected DateTimeInterface $expire;

    public function __construct(string $token, DateTimeInterface $expire)
    {
        $this->token = $token;
        $this->expire = $expire;
    }

    public function getToken(): string
    {       
       return $this->token;
    }

    public function getExpire(): DateTimeInterface
    {       
       return $this->expire;
    }
}
