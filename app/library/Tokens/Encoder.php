<?php

namespace Library\Tokens;

use Firebase\JWT\JWT;

use Library\Tokens\Token;

class Encoder
{
    private string $algorithm;

    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function encode(Token $token, string $key) : string
    {
        return JWT::encode($token->getClaims(), $key, $this->algorithm); 
    }
      
}