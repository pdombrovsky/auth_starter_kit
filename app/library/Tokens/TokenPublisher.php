<?php

namespace Library\Tokens;

use Library\Tokens\Encoder;
use Library\Tokens\Builders\AbstractBuilder;
use Library\Tokens\EncodedToken;

class TokenPublisher
{
    protected AbstractBuilder $builder;
    protected Encoder $encoder;
    protected string $key;

    public function __construct(AbstractBuilder $builder, Encoder $encoder)
    {
        $this->builder = $builder;
        $this->encoder = $encoder;
    }

    public function setKey(string $key) : TokenPublisher
    {
        $this->key = $key;
        return $this;
    }

    public function getBuilder() : AbstractBuilder
    {
        return $this->builder;
    }

    public function publish(): EncodedToken
    {
        $token = $this->builder->getToken();
        $encoded = $this->encoder->encode($token, $this->key);
        return new EncodedToken($encoded, $token->getExpire());
    }
}
