<?php

return [
    [
        'pattern' => '/login',
        'method' => 'POST',
        'namespace' => 'App\Controllers',
        'controller' => 'Authentication',
        'action' => 'login'
    ],
    [
        'pattern' => '/auth/refresh',
        'method' => 'POST',
        'namespace' => 'App\Controllers',
        'controller' => 'Authentication',
        'action' => 'refresh'
    ],
    [
        'pattern' => '/users/test',
        'method' => ['GET', 'POST'],
        'namespace' => 'App\Controllers',
        'controller' => 'Authentication',
        'action' => 'test'
    ],
];
