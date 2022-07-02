<?php

/**
 * Composer autoload
 */
$loader->registerFiles(
    [
        realpath(__DIR__ . '/../../vendor/autoload.php'),
    ]
);

/**
 * Default namespaces
 */
$loader->registerNamespaces(
    [
        'App\Listeners' => realpath(__DIR__ . '/../listeners'),
        'App\Models' => realpath(__DIR__ . '/../models'),
        'App\Services' => realpath(__DIR__ . '/../services'),
        'App\Controllers' => realpath(__DIR__ . '/../controllers'),

        'Library\Messages' => realpath(__DIR__ . '/../library/Messages'),
        'Library\Logger' => realpath(__DIR__ . '/../library/Logger'),
        'Library\ErrorHandlers' => realpath(__DIR__ . '/../library/ErrorHandlers'),
        'Library\Tokens' => realpath(__DIR__ . '/../library/Tokens'),
        'Library\Http' => realpath(__DIR__ . '/../library/Http'),
        'Library\Services' => realpath(__DIR__ . '/../library/Services'),
    ]
);
