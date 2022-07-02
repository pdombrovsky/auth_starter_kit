<?php

return [
    [
        'eventType' => 'dispatch:beforeDispatchLoop',
        'handler' => 'App\Listeners\AuthorizationListener'
    ],
    [
        'eventType' => 'dispatch:beforeException',
        'handler' => 'App\Listeners\LoggerListener'
    ],
    [
        'eventType' => 'dispatch:beforeException',
        'handler' => 'App\Listeners\ExceptionRedirectorListener'
    ],
    /*
    [
        'eventType' => 'abstractService:beforeValidationExceptionThrown',
        'handler' => 'App\Listeners\ValidationFailedListener'
    ]
    */
];
