<?php

use Phalcon\Di;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Mvc\Dispatcher;
use Library\ErrorHandlers\LoggerInterceptor;
use Library\ErrorHandlers\HandlerRegistrator;

class Application extends BaseApplication
{
    public function initialize()
    {
        $this->setLoader();
        $this->setServices();
        $this->setRouter();
        $this->registerErrorHandlers();
        $this->setListeners();
        $this->useImplicitView(false);
    }

    private function setLoader()
    {
        $loader = new Phalcon\Loader();
       
        include APP_PATH . '/config/loader.php';

        $loader->register();
    }

    private function setServices()
    {
        $container = new Di();

        include APP_PATH . '/config/services.php';

        $this->setDI($container);
    }

    private function setRouter()
    {
        $container = $this->getDI();

        $router = $container->getRouter();
       
        $routes = include APP_PATH . '/config/routes.php';

        foreach ($routes as $route) {
            $router->add($route['pattern'], [
                'namespace' => $route['namespace'],
                'controller' => $route['controller'],
                'action' => $route['action']
            ], $route['method']);
        }

        $router->notFound(
            [
                'namespace' => 'App\Controllers',
                'controller' => 'Errors',
                'action' => 'notFound'
            ]
        );

        $router->removeExtraSlashes(true);
    }

    private function setListeners()
    {
        $container = $this->getDI();

        $eventsManager = $container->getEventsManager();

        $listeners = include APP_PATH . '/config/listeners.php';

        foreach ($listeners as $listener) {
            $eventsManager->attach($listener['eventType'], new $listener['handler']);
        }
        
        $this->setEventsManager($eventsManager);
    }

    private function registerErrorHandlers()
    {
        $logger = $this->container->getErrorLogger();
        $interceptor = new LoggerInterceptor($logger);
        HandlerRegistrator::register($interceptor);
    }

    public function handleError(\Exception $e)
    {
        $logger = $this->container->getErrorLogger();
        $logger->save($e);
        echo "Error: (" . $e->getCode() . ')' . $e->getMessage();
        echo get_class($e), ': ', $e->getMessage(), '\n';
        echo ' File=', $e->getFile(), '\n';
        echo ' Line=', $e->getLine(), '\n';
        echo $e->getTraceAsString();
    }
}
