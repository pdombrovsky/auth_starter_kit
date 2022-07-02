<?php

namespace Library\Tokens\Builders;

use Library\Tokens\Token;

use Library\Tokens\Builders\AbstractBuilder;

class AccessTokenBuilder extends AbstractBuilder
{
    
    public function getToken(): Token
    {       
        $token = new Token();
        $token
        ->setExpire($this->now->modify($this->lifetime))
        ->setIssuedAt($this->now)
        ->setIssuer($this->issuer)
        ->setId($this->uuid);
        return $token;
    }
}
