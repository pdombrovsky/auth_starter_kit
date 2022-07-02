<?php

namespace Library\Http\Constants;

use Library\Services\Constants\ErrorConstants;
use Library\Services\Constants\ValidationConstants;
use Library\Http\Constants\HttpErrors;
use Library\Http\Constants\Exceptions\OutOfBoundsException;

class HttpConstants
{
    const OK                    = 200;
    const CREATED               = 201;
    const ACCEPTED              = 202;
    const MOVED_PERMANENTLY     = 301;
    const FOUND                 = 302;
    const TEMPORARY_REDIRECT    = 307;
    const PERMANENTLY_REDIRECT  = 308;
    const BAD_REQUEST           = 400;
    const UNAUTHORIZED          = 401;
    const FORBIDDEN             = 403;
    const NOT_FOUND             = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED       = 501;
    const BAD_GATEWAY           = 502;

    private static $description = [
        self::OK                    => 'OK',
        self::CREATED               => 'Created',
        self::ACCEPTED              => 'Accepted',
        self::MOVED_PERMANENTLY     => 'Moved Permanently',
        self::FOUND                 => 'Found',
        self::TEMPORARY_REDIRECT    => 'Temporary Redirect',
        self::PERMANENTLY_REDIRECT  => 'Permanent Redirect',
        self::BAD_REQUEST           => 'Bad Request',
        self::UNAUTHORIZED          => 'Unauthorized',
        self::FORBIDDEN             => 'Forbidden',
        self::NOT_FOUND             => 'Not Found',
        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::NOT_IMPLEMENTED       => 'Not Implemented',
        self::BAD_GATEWAY           => 'Bad Gateway',
    ];

    public static function convertErrorConstant(int $apiError) : int
    {
        switch ($apiError) {
            case ErrorConstants::BAD_EMAIL:
            case ErrorConstants::BAD_PASSWORD:
            case ErrorConstants::VALIDATION_FAILED;
                return self::BAD_REQUEST;
            case ErrorConstants::TOKEN_EXPIRED:
            case ErrorConstants::REFRESH_TOKEN_EXPIRED:
                return self::UNAUTHORIZED;
            case ErrorConstants::BROKEN_SIGNATURE:
            case ErrorConstants::TOO_EARLY:
            case ErrorConstants::BROKEN_TOKEN:
            case ErrorConstants::BLOCKED_USER:
            case ErrorConstants::UNKNOWN_CLIENT:
            case ErrorConstants::BLOCKED_CLIENT:
            case HttpErrors::AUTH_HEADER_REQUIRED:
            case HttpErrors::BEARER_REQUIRED:
            case ErrorConstants::BAD_REFRESH_TOKEN:
            case ErrorConstants::SESSION_CLOSED:
            case ErrorConstants::CLIENT_UNKNOWN:
                return self::FORBIDDEN;
            case ErrorConstants::SERVICE_ERROR:
                return self::INTERNAL_SERVER_ERROR;
            default:
                return self::INTERNAL_SERVER_ERROR;
        }
    }

    public static function getDescription(int $code)
    {
        if (!key_exists($code, self::$description)) {
            throw new OutOfBoundsException('Can not get description by providen code.');
        }
        return self::$description[$code];
    }
}
