<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sessions;

use Library\Services\Exceptions\ServiceException;

class SessionInterrupterService
{
    public function breake(string $refreshToken) : void
    {
        $session = Sessions::findFirstByUuid($refreshToken);
        if (!$session) {

            throw new ServiceException('Refresh token not found', ErrorConstants::BAD_REFRESH_TOKEN);
        }
        $session->close();
    }
}
