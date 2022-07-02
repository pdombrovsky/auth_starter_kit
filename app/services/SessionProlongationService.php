<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sessions;
use App\Models\Clients;

use App\Services\TokenRepublisherService;
use App\Services\Republisher\TokenRepublisherResult;

use Library\Services\Constants\ErrorConstants;
use Library\Services\Exceptions\ServiceException;
use Library\Services\Exceptions\ValidationException;

use Phalcon\Messages\Messages;

use DateTimeImmutable;

class SessionProlongationService
{
    private TokenRepublisherService $tokenRepublisher;

    public function __construct(TokenRepublisherService $tokenRepublisher)
    {
        $this->tokenRepublisher = $tokenRepublisher;
    }

    public function extend(string $refreshToken, string $fingerPrint) : TokenRepublisherResult
    {
        $session = Sessions::findFirstByUuid($refreshToken);

        $result = self::checkSession($session);
        if ($result) {

            throw new ServiceException($result['message'], $result['code']);
        }

        $client = $session->getClients();

        $result = self::checkClient($client, $fingerPrint);
        if ($result) {

            throw new ServiceException($result['message'], $result['code']);
        }

        $newSessionUuid = Sessions::createUuid();
        $tokens = $this->tokenRepublisher->republish($session->getUserUuid(), $newSessionUuid);
        $session->prolongate($newSessionUuid, $tokens->getRefreshToken()->getExpire());
        
        return $tokens;
    }

    private static function checkSession(?Sessions $session) : ?array
    {
        if (!$session) {
        
            return ['message' => 'Refresh token not found', 'code' => ErrorConstants::BAD_REFRESH_TOKEN];
        }

        if (!$session->getAccessible()) {

            return ['message' => 'Session closed', 'code' => ErrorConstants::SESSION_CLOSED];
        }

        if ($session->getValidThrough() < new DateTimeImmutable('now')) {

            return ['message' => 'Refresh token expired', 'code' => ErrorConstants::REFRESH_TOKEN_EXPIRED];
        }
        
        return null;
    }

    private static function checkClient(Clients $client, string $fingerPrint) : ?array
    {
        if ($client->getFingerPrint() !== $fingerPrint) {

            return ['message' => 'Unknown client', 'code' => ErrorConstants::CLIENT_UNKNOWN];
        }

        if (!$client->getAccessible()) {

            return ['message' => 'Client is blocked', 'code' => ErrorConstants::BLOCKED_CLIENT];
        }
        return null;
    }

}
