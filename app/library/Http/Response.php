<?php

namespace Library\Http;

use Phalcon\Http\Response as BaseResponse;
use Phalcon\Http\ResponseInterface;
use Phalcon\Messages\Message;

use Library\Http\Constants\HttpConstants;
use Library\Messages\MessageFactory;
use Library\Messages\MessagesConverter;

use Throwable;

class Response extends BaseResponse
{
    public function setJsonContent($content, int $jsonOptions = 0, int $depth = 512) : ResponseInterface
    {
        parent::setJsonContent($content, $jsonOptions, $depth);
        $this->setContentType('application/vnd.api+json');
        return $this;
    }

    public function setError(Throwable $ex)
    {
        $error = [
            'errors' => [
                'status' => $ex->getCode(),
                'detail' => $ex->getMessage()
            ]
        ];
        $this->setDataContent(HttpConstants::convertErrorConstant($ex->getCode()), $error);
    }

    public function setErrorsContent(int $httpConstant, $messages)
    {
        $errors = $this->createErrorsResponse($messages);
        $this->setDataContent($httpConstant, $errors);
    }

    public function setDataContent(int $httpConstant, array $data)
    {
        $this
        ->setStatusCode($httpConstant, HttpConstants::getDescription($httpConstant))
        ->setJsonContent($data);
    }

    private function createErrorsResponse($messages) : array
    {
        return ['errors' => MessagesConverter::convertCollectionForApi($messages)];
    }
}
