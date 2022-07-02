<?php

namespace Library\Tokens\Decoders;

use Library\Tokens\Decoders\AbstractDecoder;
use Library\Tokens\Token;

class AccessTokenDecoder extends AbstractDecoder
{
    public function decode(string $token, string $key) : Token
    {
        $tokenStd = self::getDecodedToken($token, $key, $this->algorithm);
        return new Token((array) $tokenStd);
    }
        
}

