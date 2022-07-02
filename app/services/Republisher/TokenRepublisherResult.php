<?php

declare(strict_types=1);

namespace App\Services\Republisher;

use Library\Tokens\EncodedToken;

class TokenRepublisherResult
{
    protected EncodedToken $accessToken;
    protected EncodedToken $refreshToken;

    public function __construct(EncodedToken $accessToken, EncodedToken $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function getAccessToken() : EncodedToken
    {
        return $this->accessToken;
    }

    public function getRefreshToken() : EncodedToken
    {
        return $this->refreshToken;
    }
}
