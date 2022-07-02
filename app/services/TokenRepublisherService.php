<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sessions;
use App\Services\Republisher\TokenRepublisherResult;

use Library\Tokens\TokenPublisher;
use Library\Tokens\EncodedToken;

use DateTimeImmutable;
use DateTimeInterface;

class TokenRepublisherService
{
    private TokenPublisher $accessTokenPublisher;
    private string $refreshTokenLifetime;

    public function __construct(TokenPublisher $accessTokenPublisher, string $refreshTokenLifetime)
    {
        $this->accessTokenPublisher = $accessTokenPublisher;
        $this->refreshTokenLifetime = $refreshTokenLifetime;
    }

    public function republish(string $userUuid, string $sessionUuid) : TokenRepublisherResult
    {   
        $now = new DateTimeImmutable('now');
        $accessToken = $this->getAccessToken($now, $userUuid);
        $refreshToken = new EncodedToken($sessionUuid, $now->modify($this->refreshTokenLifetime));

        return new TokenRepublisherResult($accessToken, $refreshToken);
    }

    private function getAccessToken(DateTimeInterface $now, string $userUuid) : EncodedToken
    {
        $this->accessTokenPublisher->getBuilder()
        ->setNow($now)
        ->setUuid($userUuid);
        return $this->accessTokenPublisher->publish();
    }
}
