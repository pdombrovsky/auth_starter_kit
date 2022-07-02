<?php

namespace Library\Tokens\Decoders;

use Library\Tokens\Exceptions\ExpiredException;
use Library\Tokens\Exceptions\BeforeValidException;
use Library\Tokens\Exceptions\SignatureInvalidException;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Library\Tokens\Token;

use stdClass;

abstract class AbstractDecoder
{
    
    protected string $algorithm;

    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    abstract public function decode(string $token, string $key) : Token;

    protected static function getDecodedToken(
        string $token,
        string $key,
        string $algorithm
    ) : stdClass {
        try {
            return JWT::decode(
                $token,
                new Key($key, $algorithm)
            );
        } catch (Firebase\JWT\SignatureInvalidException $ex) {
            throw new SignatureInvalidException($ex->getMessage());
        } catch (Firebase\JWT\BeforeValidException $ex) {
            throw new BeforeValidException($ex->getMessage());
        } catch (Firebase\JWT\ExpiredException $ex) {
            throw new ExpiredException($ex->getMessage());
        }
    }
}
