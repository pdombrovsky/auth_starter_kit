<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'mariadb',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'test_case',
        'charset'     => 'utf8',
    ],

    'application' => [
        'appDir'         => APP_PATH . '/',
        'configDir'      => APP_PATH . '/config/',
        'libraryDir' => APP_PATH . '/library/',
        'commonlsDir'      => APP_PATH . '/common/',
        'moduleslsDir'      => APP_PATH . '/modules/',
        'modelsDir'      => APP_PATH . '/models/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],

    'whiteList' => [
        'login',
        'refresh'
    ],

    'crypt' => [

        'algorithm' => 'aes-256-ctr',
    ],

    'cookies' => [

        'useEncryption' => false,
        'key' => '#1dj8$=dp?.ak//j1V$~%*0XaK\xb1\x8d\xa9\x98\x054t7w!z%C*F-Jk\x98\x05\\\x5c'

    ],

    'cache' => [
        'adapter' => 'Apcu', // Available ['Apcu', 'Libmemcached', 'Memory'] - use 'Memory' in dev mode

        'apcuOptions' => [
            'defaultSerializer' => 'Php', // Available ['None', 'Php', 'Msgpack', 'Json', 'Igbinary', 'Base64']
            'lifetime'          => 3600,
            'prefix'            => 'ph-apcu-'
        ],

        'libmemcachedOptions' => [
            'defaultSerializer' => 'Php', // Available ['None', 'Php', 'Msgpack', 'Json', 'Igbinary', 'Base64']
            'lifetime'          => 3600,
            'prefix'            => 'ph-memc-',
            'servers' => [
                0 => [
                    'host'   => 'memcached', //'127.0.0.1',
                    'port'   => 11211,
                    'weight' => 1
                ],  
            ],
            /*
            'persistentId' => 'ph-mcid-',
            'saslAuthData' => [
                'user' => '',
                'pass' => ''
            ],
            'client' => [
                \Memcached::OPT_CONNECT_TIMEOUT => 10,
                \Memcached::OPT_DISTRIBUTION => \Memcached::DISTRIBUTION_CONSISTENT,
                \Memcached::OPT_SERVER_FAILURE_LIMIT => 2,
                \Memcached::OPT_REMOVE_FAILED_SERVERS => true,
                \Memcached::OPT_RETRY_TIMEOUT => 1
            ],
            */
        ],

        'memoryOptions' => [
            'defaultSerializer' => 'Php', // Available ['None', 'Php', 'Msgpack', 'Json', 'Igbinary', 'Base64']
            'lifetime'          => 3600,
            'prefix'            => 'ph-memo-'
        ],

    ],

    'modelsMetaData' => [

        'adapter' => 'Memory', //['Apcu', 'Libmemcached', 'Memory'] - use 'Memory' in dev mode
        
        'apcuOptions' => [
            'lifetime' => 172000,
            'prefix'   => 'ph-mm-apcu-',
        ],

        'libmemcachedOptions' => [
            'lifetime' => 172000,
            'prefix'   => 'ph-mm-memc-',
            'servers' => [
                0 => [
                    'host'   => 'memcached', //'127.0.0.1',
                    'port'   => 11211,
                    'weight' => 1
                ],   
            ],
        ]
    ],

    'errorLogPath' => BASE_PATH . '/logs/errors.log',
    'messageLogPath' => BASE_PATH . '/logs/messages.log',

    'dateTimeFormat' => 'Y-m-d H:i:s',

    'accessToken' => [
        'algorithm' => 'HS256',
        'lifeTime' => '5 min',
        'key' => 'AcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2',
    ],

    'sessionLifetime' => '2 day',

    'refreshTokenCookie' => [
        'name' => 'refresh',
        'prefix' => '', // [ '__Host-', '__Secure-']
        'options' => [
            'path' => '/auth',
            'secure' => false,
            'domain' => '',
            'httpOnly' => false,
            'samesite' => 'None', // ['Strict', 'Lax', 'None']
        ]
    ],

    'fingerPrintCookie' => [
        'name' => 'fgprt',
        'expire' => '1 year',
        'prefix' => '', // [ '__Host-', '__Secure-']
        'options' => [
            'path' => '/',
            'secure' => false,
            'domain' => '',
            'httpOnly' => false,
            'samesite' => 'None', // ['Strict', 'Lax', 'None']
        ]
    ],
]);
