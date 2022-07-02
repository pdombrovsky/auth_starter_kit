<?php

use Phalcon\Config;
use Phalcon\Cli\Dispatcher;
use Library\Logger\Factories\LoggerFactory;

/**
 * Shared configuration service
 */
$container->setShared(
    'config',
    function () {
        return require_once APP_PATH . '/config/config.php';
    }
);

/**
 * ErrorLogger is created the first time some component request the logger
 */
$container->setShared(
    'errorLogger',
    function () {
        $filePath = $this->getConfig()->errorLogPath;
        return LoggerFactory::createErrorLogger($filePath);
    }
);

/**
 * MessageLogger is created the first time some component request the logger
 */
$container->setShared(
    'messageLogger',
    function () {
        $filePath = $this->getConfig()->messageLogPath;
        return LoggerFactory::createMessageLogger($filePath);
    }
);

/**
 * Shared dispatcher
 */
$container->setShared(
    'dispatcher',
    function () {
        return new Dispatcher();
    }
);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$container->setShared(
    'db',
    function () {
        $config = $this->getConfig();
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
        $params = [
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->dbname,
            'charset'  => $config->database->charset
        ];
        if ($config->database->adapter == 'Postgresql') {
            unset($params['charset']);
        }
        $connection = new $class($params);
        return $connection;
    }
);
