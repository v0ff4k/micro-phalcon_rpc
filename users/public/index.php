<?php

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
    /**
     * Include routes.
     * all in services, on the fly from JsonRpc->method
     */
    $router = $di->get('router');
    $router->handle();

    /**
     * Told to MVC that we have not both: view,render and content.
     * So we work only what return by Response() from controller!
     */
    $view = $di->getShared('view');

    $dispatcher = $di->getShared('dispatcher');

    $dispatcher->setControllerName($router->getControllerName());
    $dispatcher->setActionName($router->getActionName());
    $dispatcher->setParams($router->getParams());

    /**
     * process
     */
    $dispatcher-> dispatch();

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