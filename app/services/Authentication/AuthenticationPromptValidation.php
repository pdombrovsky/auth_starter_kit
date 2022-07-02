<?php

namespace App\Services\Authentication;

use Phalcon\Validation;

use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\StringLength\Max;
use Phalcon\Validation\Validator\Callback;


use Library\Services\Constants\ValidationConstants;

class AuthenticationPromptValidation extends Validation
{
    public function initialize()
    {

        $this->add(
            'email',
            new Email(
                [
                    'message' => 'The email address is incorrect',
                    'code' => ValidationConstants::INVALID_EMAIL
                ]
            )
        );

        $this->add(
            'fingerPrint',
            new Callback(
                [
                    'callback' => function ($data) {
                        if (!empty($data->getFingerPrint())) {
                            return new Min(
                                [
                                    'min' => 12,
                                    'message' => 'Minimum fingerprint lenght is 12 symbols',
                                    'code' => ValidationConstants::WRONG_FGPRT_LENGTH
                                    
                                ]
                            );
                        }

                        return true;
                    }
                ]
            )
        );
        
        $this->add(
            'fingerPrint',
            new Callback(
                [
                    'callback' => function ($data) {
                        if (!empty($data->getFingerPrint())) {
                            return new Max(
                                [
                                    'max' => 100,
                                    'messageMaximum' => 'Maximum fingerprint lenght is 100 symbols',
                                    'code' => ValidationConstants::WRONG_FGPRT_LENGTH
                                    
                                ]
                            );
                        }
                        
                        return true;
                    }
                ]
            )
        );
    }
}
