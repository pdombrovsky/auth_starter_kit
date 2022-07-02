<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

ini_set('display_errors', '1');
ini_set('max_execution_time', '0');

require_once APP_PATH . '/Application.php';

$application = new Application();
$application->initialize();

try {
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );
    $response->send();
} catch (\Exception $e) {
    $application->handleError($e);
}
