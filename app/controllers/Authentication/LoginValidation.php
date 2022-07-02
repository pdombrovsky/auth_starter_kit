<?php

namespace App\Controllers\Authentication;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

use Library\Http\Constants\HttpErrors;

class LoginValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            [
                'email',
                'password',
            ],
            new PresenceOf(
                [
                    'message' =>
                    [
                        'email' => 'Not empty email is required',
                        'password' => 'Not empty password is required',
                    ],
                    'code' => 
                    [
                        'email' => HttpErrors::EMAIL_REQUIRED,
                        'password' => HttpErrors::PASSWORD_REQUIRED,
                    ]
                ]
            )
        );
    }
}