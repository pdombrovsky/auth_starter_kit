<?php

declare(strict_types=1);

namespace App\Services\Authentication;

use Library\Tokens\EncodedToken;

use App\Services\Republisher\TokenRepublisherResult;


class AuthenticationResult
{
    protected EncodedToken $accessToken;
    protected EncodedToken $refreshToken;
    protected string $fingerPrint;
    protected string $firstName;

    public function __construct(TokenRepublisherResult $tokens, string $fingerPrint, string $firstName)
    {
        $this->accessToken = $tokens->getAccessToken();
        $this->refreshToken = $tokens->getRefreshToken();
        $this->fingerPrint = $fingerPrint;
        $this->firstName = $firstName;
    }

    public function getAccessToken() : EncodedToken
    {
        return $this->accessToken;
    }

    public function getRefreshToken() : EncodedToken
    {
        return $this->refreshToken;
    }

    public function getFingerPrint() : string
    {
        return $this->fingerPrint;
    }

    public function getFirstName() : string
    {
        return $this->firstName;
    }
}
