<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

date_default_timezone_set('Asia/Bishkek');

define('APP_ENV', getenv('APP_ENV') ?: 'dev');

if (APP_ENV === 'dev') {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
}

try {

    require dirname(__DIR__).'/vendor/autoload.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = include __DIR__.'/../app/config/config.php';

    /**
     * Include Autoloader.
     */
    include APP_PATH . '/config/loader.php';

    $di = new Phalcon\Di();

    /**
     * Include Services.
     */
    include APP_PATH . '/config/services.php';

    /*
     * Starting the application
     * Assign service locator to the application
     */
//    $app = new Micro(); $app->setDI($di);
//    $app = new Micro($di);

    /**
     * Include routes.
     * @todo all in services, on the fly from JsonRpc->method
     */
//    include APP_PATH . '/config/routes.php';

//    $request = $di->get('request');
    $router = $di->get('router');
    $router->handle();

    $view = $di->getShared('view');

    $dispatcher = $di->getShared('dispatcher');

    $dispatcher->setControllerName($router->getControllerName());
    $dispatcher->setActionName($router->getActionName());
    $dispatcher->setParams($router->getParams());

//    $view->start;
//var_dump($dispatcher);die;
    $dispatcher-> dispatch();
//
//    $view->render(
//        $dispatcher->getControllerName(),
//        $dispatcher->getActionName(),
//        $dispatcher->getParams()
//    );


//    $response = $di->getShared('response');
//    $response->setContent($view->getContent());
//    $response->send();

//    $app->session->start();

    /**
     * Handle the whole request
     */
//    $app->handle();

} catch (Error | Exception $e) {

    (new \app\plugins\Logger())
        ->error(
            $e->getMessage() . PHP_EOL . ' [Stack Trace:]' . PHP_EOL . $e->getTraceAsString()
        );

    if (APP_ENV === 'dev') {
       $content = sprintf(
            "%s, stacktrace: %s ",
            $e->getMessage(),
            $e->getTraceAsString()
       );
        $content = str_replace("\r\n","", $content);
    } else {
        $content = 'There was an error processing your request';
    }

    \app\plugins\JsonHelper::returnRpcResponse($content, true, -32603, 1, 500);
    die;
}