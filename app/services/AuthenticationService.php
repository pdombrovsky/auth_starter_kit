<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Users;
use App\Models\Sessions;
use App\Models\Clients;

use App\Services\TokenRepublisherService;
use App\Services\Authentication\AuthenticationPrompt;
use App\Services\Authentication\AuthenticationResult;
use App\Services\Authentication\AuthenticationPromptValidation;

use Library\Services\Constants\ErrorConstants;
use Library\Services\Exceptions\ServiceException;
use Library\Services\Exceptions\ValidationException;

use Phalcon\Messages\Messages;

class AuthenticationService
{
    private TokenRepublisherService $tokenRepublisher;
    private AuthenticationPromptValidation $validation;

    public function __construct(TokenRepublisherService $tokenRepublisher, AuthenticationPromptValidation $validation)
    {
        $this->tokenRepublisher = $tokenRepublisher;
        $this->validation = $validation;
    }

    public function authenticate(AuthenticationPrompt $prompt) : AuthenticationResult
    {
        $messages = $this->getValidationMessages($prompt);

        if($messages->count()) {

            throw new ValidationException($messages,'Invalid authentication data', ErrorConstants::VALIDATION_FAILED);
        }

        $client = null;

        if(!empty($prompt->getFingerPrint())) {

            $client = Clients::findFirstByFingerPrint($prompt->getFingerPrint());
            if (!$client) {

                throw new ServiceException('Unknown client', ErrorConstants::UNKNOWN_CLIENT);   
            } 

            $client->closeOpenSessions();

            if (!$client->getAccessible()) {

                throw new ServiceException('Client is blocked', ErrorConstants::BLOCKED_CLIENT);
            }

        }

        $user = Users::findFirstByEmail($prompt->getEmail());

        $result = self::checkUser($user, $prompt->getPassword());
        if ($result) {

            throw new ServiceException($result['message'], $result['code']);
        }

        if (!$client) {

            $fingerPrint = Clients::createFingerPrint();
            $client = Clients::createModel($fingerPrint);
        }

        $newSessionUuid = Sessions::createUuid();
        $tokens = $this->tokenRepublisher->republish($user->getUuid(), $newSessionUuid);
        $session = Sessions::createModel(
            $user->getUuid(),
            $client->getId(),
            $tokens->getRefreshToken()->getExpire(),
            $newSessionUuid
        );

        return new AuthenticationResult($tokens, $client->getFingerPrint(), $user->getFirstName());
    }

    private function getValidationMessages(AuthenticationPrompt $prompt) : Messages
    {
        $this->validation->setEntity($prompt);
        return $this->validation->validate();
    }

    private static function checkUser(?Users $user, string $password) : ?array
    {
        
        if ($user === null) {

            return ['message' => 'There are no user with provided email', 'code' => ErrorConstants::BAD_EMAIL];
        };

        if (!$user->validatePassword($password)) {

            return ['message' => 'Incorrect password', 'code' => ErrorConstants::BAD_PASSWORD];
        }

        if (!$user->getActive()) {

            return ['message' => 'User is inactive', 'code' => ErrorConstants::BLOCKED_USER];
        }

        return null;
    }
}
