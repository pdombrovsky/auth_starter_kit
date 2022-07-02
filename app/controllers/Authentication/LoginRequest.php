<?php

namespace App\Controllers\Authentication;

use App\Services\Authentication\AuthenticationPrompt;
use App\Controllers\Authentication\LoginValidation;

use Phalcon\Messages\Messages;
use Phalcon\Http\Request;
use Library\Http\Cookie;

class LoginRequest
{
    protected Request $request;
    protected LoginValidation $validation;
    protected ?string $fingerPrint;

    public function __construct(Request $request, ?string $fingerPrint, LoginValidation $validation)
    {
        $this->request = $request;
        $this->fingerPrint = $fingerPrint;
        $this->validation = $validation;
    }

    public function tryGetAuthenticationPrompt(?AuthenticationPrompt &$prompt) : Messages
    {
        $params = $this->getParams();
        $messages = $this->validation->validate($params);
        $prompt = null;

        if (!$messages->count()) {

            $args = array_values($params);
            $prompt = new AuthenticationPrompt(...$args);
        }
        return $messages;
    }

    private function getParams() : array
    {
        return [
            'email' => $this->request->getPost('email', 'email'),
            'password' => $this->request->getPost('password', 'string'),
            'fingerprint' => ($this->fingerPrint === null) ? '' : $this->fingerPrint
        ];
    }
}
