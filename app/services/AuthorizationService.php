<?php

namespace App\Services;

use Library\Tokens\Token;
use Library\Tokens\Decoders\AccessTokenDecoder;

use Library\Tokens\Exceptions\ExpiredException;
use Library\Tokens\Exceptions\BeforeValidException;
use Library\Tokens\Exceptions\SignatureInvalidException;

use Library\Services\Constants\ErrorConstants;
use Library\Services\Exceptions\ServiceException;
use Library\Services\Exceptions\ServiceRuntimeException;

use UnexpectedValueException;
use Throwable;

class AuthorizationService
{
    private string $key;
    private AccessTokenDecoder $decoder;

    public function __construct(string $key, AccessTokenDecoder $decoder)
    {
        $this->key = $key;
        $this->decoder = $decoder;
    }

    public function authorize(string $token) : bool
    {
        $token = $this->decodeAccessToken($token);
        return true;
    }

    private function decodeAccessToken(string $token) : Token
    {

        try {

            return $this->decoder->decode($token, $this->key);

        } catch (SignatureInvalidException $ex) {

            throw new ServiceException($ex->getMessage(), ErrorConstants::BROKEN_SIGNATURE, $ex);

        } catch (BeforeValidException $ex) {

            throw new ServiceException($ex->getMessage(), ErrorConstants::TOO_EARLY, $ex);

        } catch (ExpiredException $ex) {

            throw new ServiceException($ex->getMessage(), ErrorConstants::TOKEN_EXPIRED, $ex);

        } catch (UnexpectedValueException $ex) {

            throw new ServiceException($ex->getMessage(), ErrorConstants::BROKEN_TOKEN, $ex);

        } catch (Throwable $ex) {

            throw new ServiceRuntimeException(
                'An error occurred while decoding access token',
                ErrorConstants::SERVICE_ERROR,
                $ex
            );
        }
    }
}
