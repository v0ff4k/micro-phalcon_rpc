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

    $di = new FactoryDefault();

    /**
     * Include Services.
     */
    include APP_PATH . '/config/services.php';

    /*
     * Starting the application
     * Assign service locator to the application
     */
//    $app = new Micro(); $app->setDI($di);
    $app = new Micro($di);

    /**
     * Include routes.
     */
    include APP_PATH . '/config/routes.php';

    /**
     * Init the Session
     */
    $app->session->start();

    /**
     * Handle the whole request
     */
    $app->handle();

} catch (Error | Exception $e) {

    (new \app\plugins\Logger())
        ->error(
            $e->getMessage() . PHP_EOL . ' [Stack Trace:]' . PHP_EOL . $e->getTraceAsString()
        );

    if (APP_ENV === 'dev') {
       $content = sprintf(
            "message: %s, \n stacktrace: %s ",
            $e->getMessage(),
            $e->getTraceAsString()
       );
    } else {
        $content = 'There was an error processing your request';
    }

    \app\plugins\JsonHelper::returnJsonRpcResponse($content, true, -32603, 1, 500);
    die;
}