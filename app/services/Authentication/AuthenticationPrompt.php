<?php

declare(strict_types=1);

namespace App\Services\Authentication;

class AuthenticationPrompt
{
    protected string $email;
    protected string $password;
    protected string $fingerPrint;

    public function __construct(string $email, string $password, string $fingerPrint)
    {
        $this->email = $email;
        $this->password = $password;
        $this->fingerPrint = $fingerPrint;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string 
    {
        return $this->password;
    }

    public function getFingerPrint() : string
    {
        return $this->fingerPrint;
    }
}
