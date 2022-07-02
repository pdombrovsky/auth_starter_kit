<?php

declare(strict_types=1);

namespace App\Services\Prolongation;

class SessionProlongationPrompt
{
    protected string $refreshToken;
    protected string $fingerPrint;

    public function __construct(string $refreshToken, string $fingerPrint)
    {
        $this->refreshToken = $refreshToken;
        $this->fingerPrint = $fingerPrint;
    }

    public function getRefreshToken() : string
    {
        return $this->refreshToken;
    }

    public function getFingerPrint() : string
    {
        return $this->fingerPrint;
    }
}
