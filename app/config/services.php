<?php

use Phalcon\Crypt;
use Phalcon\Config;
use Phalcon\Security;
use Phalcon\Mvc\Router;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Security\Random;
use Phalcon\Cache\CacheFactory;
use Phalcon\Filter\FilterFactory;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Http\Response\Cookies;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

use Library\Http\Response;
use Library\Http\Cookie;
use Library\Tokens\Encoder;
use Library\Tokens\TokenPublisher;
use Library\Logger\Factories\LoggerFactory;
use Library\Tokens\Builders\AccessTokenBuilder;
use Library\Tokens\Decoders\AccessTokenDecoder;

use App\Services\AuthorizationService;
use App\Services\AuthenticationService;
use App\Services\TokenRepublisherService;
use App\Services\SessionProlongationService;
use App\Services\Authentication\AuthenticationPromptValidation;

$container->setShared(
    'crypt',
    function () {
        $options = $this->getConfig()->crypt;
        return new Crypt($options->algorithm);
    }
);

$container->setShared(
    'cookies',
    function () {
        $options = $this->getConfig()->cookies;
        return new Cookies($options->useEncryption, $options->key);
    }
);

$container->setShared(
    'security',
    function () {
        $security = new Security();
        return $security;
    }
);

$container->setShared(
    'random',
    new Random()
);

$container->setShared(
    'filter',
    function () {
        $filter = new FilterFactory();
        return $filter->newInstance();
    }
);

$container->setShared(
    'request',
    new Request()
);

$container->setShared(
    'eventsManager',
    new EventsManager()
);

$container->setShared(
    'modelsManager',
    new ModelsManager()
);

$container->setShared(
    'transactionManager',
    new TransactionManager()
);

$container->setShared(
    'modelsMetadata',
    function () {
        $metaData = $this->getConfig()->modelsMetaData;
        $class = 'Phalcon\Mvc\Model\MetaData\\' . $metaData->adapter;

        if ($metaData->adapter === 'Memory') {
            return new $class();
        }

        $serializerFactory = new SerializerFactory();
        $adapterFactory    = new AdapterFactory($serializerFactory);
        $optionsKey = lcfirst($metaData->adapter) . 'Options';
        $options = $metaData->{$optionsKey};
        return new $class($adapterFactory, $options->toArray());
    }
);

$container->setShared(
    'cache',
    function () {

        $cache = $this->getConfig()->cache;
        $adapter = lcfirst($cache->adapter);
        $optionsKey = $adapter . 'Options';
        $options = $cache->{$optionsKey};

        $serializerFactory = new SerializerFactory();
        $adapterFactory    = new AdapterFactory(
            $serializerFactory,
            $options->toArray()
        );
        
        $cacheFactory = new CacheFactory($adapterFactory);

        return $cacheFactory->newInstance($adapter);
    }
);

$container->setShared(
    'dispatcher',
    function () {
        $eventsManager = $this->getEventsManager();
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    }
);

$container->setShared(
    'config',
    function () {
        $config = require_once APP_PATH . '/config/config.php';
        return $config;
    }
);


$container->setShared(
    'errorLogger',
    function () {
        $filePath = $this->getConfig()->errorLogPath;
        return LoggerFactory::createErrorLogger($filePath);
    }
);


$container->setShared(
    'messageLogger',
    function () {
        $filePath = $this->getConfig()->messageLogPath;
        return LoggerFactory::createMessageLogger($filePath);
    }
);


$container->setShared(
    'router',
    new Router(false)
);

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

$container->setShared(
    'response',
    new Response()
);


$container->setShared(
    'authorizationService',
    function () {
        $accessToken = $this->getConfig()->accessToken;
        $decoder = new AccessTokenDecoder($accessToken->algorithm);
        return new AuthorizationService($accessToken->key, $decoder);
    }
);

$container->setShared(
    'refreshTokenCookie',
    function () {
        $config = $this->getConfig()->refreshTokenCookie;
        $cookies = $this->getCookies();
        return new Cookie($config, $cookies);
    }
);

$container->setShared(
    'fingerPrintCookie',
    function () {
        $config = $this->getConfig()->fingerPrintCookie;
        $cookies = $this->getCookies();
        return new Cookie($config, $cookies);
    }
);


$container->setShared(
    'tokenRepublisherService',
    function () {

        $issuer = $this->getRequest()->getServer('SERVER_NAME');
        $accessTokenConfig = $this->getConfig()->accessToken;
        $builder = new AccessTokenBuilder($issuer, $accessTokenConfig->lifetime);

        $encoder = new Encoder($accessTokenConfig->algorithm);

        $publisher = new TokenPublisher($builder, $encoder);
        $publisher->setKey($accessTokenConfig->key);
        $sessionLifetime = $this->getConfig()->sessionLifetime;

        return new TokenRepublisherService($publisher, $sessionLifetime);
    }
);

$container->setShared(
    'authenticationService',
    function () {
        
        $tokenRepublisherService = $this->getTokenRepublisherService();
        $authenticationService = new AuthenticationService($tokenRepublisherService, new AuthenticationPromptValidation());

        return $authenticationService;
    }
);

$container->setShared(
    'sessionProlongationService',
    function () {
        $tokenRepublisherService = $this->getTokenRepublisherService();
        return new SessionProlongationService(
            $tokenRepublisherService
        );
    }
);
