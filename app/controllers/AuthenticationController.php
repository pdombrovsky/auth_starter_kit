<?php

namespace App\Controllers;

use App\Models\Users;
use App\Controllers\BaseController;

use Library\Tokens\EncodedToken;
use Library\Http\Constants\HttpErrors;
use Library\Http\Constants\HttpConstants;
use Library\Http\Exceptions\HttpException;
use Library\Http\Exceptions\HttpValidationException;
use Library\Services\Exceptions\ServiceException;

use App\Services\Authentication\AuthenticationService;
use App\Services\Authentication\AuthenticationPrompt;

use App\Controllers\Authentication\LoginValidation;
use App\Controllers\Authentication\LoginRequest;

class AuthenticationController extends BaseController
{
    public function loginAction()
    {
        $login = new LoginRequest($this->request, $this->fingerPrintCookie->get(), new LoginValidation());

        $prompt = null;
        $messages = $login->tryGetAuthenticationPrompt($prompt);
        if ($messages->count()) {
            throw new HttpValidationException($messages);
        }

        $authenticationResult = $this->authenticationService->authenticate($prompt);
        
        $refreshToken = $authenticationResult->getRefreshToken();
        $this->setCookies($refreshToken, $authenticationResult->getFingerPrint());
        
        $accessToken = $authenticationResult->getAccessToken();
        $this->response->setDataContent(
            HttpConstants::OK,
            [
                'data' =>
                    [
                        'token' => $accessToken->getToken(),
                        'expire' => $accessToken->getExpire()->getTimeStamp(),
                        'firstName' => $authenticationResult->getFirstName()
                    ]
            ]
        );
    }

    private function setCookies(EncodedToken $refreshToken, string $fingerPrint)
    {
        $this->refreshTokenCookie->set($refreshToken->getToken(), $refreshToken->getExpire()->getTimeStamp());
        $fingerPrintExpires = $refreshToken->getExpire()->modify($this->config->fingerPrintCookie->expire);
        $this->fingerPrintCookie->set($fingerPrint, $fingerPrintExpires->getTimeStamp());
    }

    public function refreshAction()
    {
        
        $refreshToken = $this->refreshTokenCookie->get();
        if (!$refreshToken) {
            throw new HttpException('Refresh token is missing', HttpErrors::REFRESH_TOKEN_REQUIRED);
        }

        $fingerPrint = $this->fingerPrintCookie->get();
        if (!$fingerPrint) {
            throw new HttpException('Fingerprint is missing', HttpErrors::FINGERPRINT_REQUIRED);
        }

        try {

            $tokens = $this->sessionProlongationService->extend($refreshToken, $fingerPrint);

        } catch (ServiceException $exception) {

            $this->refreshTokenCookie->delete();
            throw $exception;
        }

        $refreshToken = $tokens->getRefreshToken();
        $this->setCookies($refreshToken, $fingerPrint);

        $accessToken = $tokens->getAccessToken();
        $this->response->setDataContent(
            HttpConstants::OK,
            [
                'data' =>
                    [
                        'token' => $accessToken->getToken(),
                        'expire' => $accessToken->getExpire()->getTimeStamp()
                    ]
            ]
        );
    }

    

    public function testAction()
    {
        
        echo __METHOD__;
        /*
        $now = new \DateTimeImmutable('now');
        $valid = $now->modify('+60 day');
        $user = new Users();
        $user
        ->setUuid(Users::createUuid())
        ->setFirstName('FirstName')
        ->setLastName('LastName')
        ->setEmail('email@gmail.com')
        ->setPasswordHash(Users::hashPassword('123456789'))
        ->setValidThrough($valid)
        ->setActive(true);

        $result = $user->save();

    */
    }
}
