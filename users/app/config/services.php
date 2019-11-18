<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Crypt;
use Phalcon\Flash\Direct;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as AdapterFiles;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
//use Phalcon\Http\Request as ClientRequest;

/**
 * Register all available services
 * like services.yml ;)
 * @var Phalcon\Config $config
 * @var Phalcon\Di\FactoryDefault $di
 * @var Phalcon\Mvc\Micro $app

 */

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});

/**
 * Loads .env file into $_ENV file
 *  $_ENV[''] | $_SERVER[''] | getenv('')
 */
$di->set('env', function() {

    $envFile = APP_PATH . '/../.env';
    if(file_exists($envFile)) {
        $env = new Dotenv\Dotenv(dirname(dirname(dirname($envFile))));
        $env->load();//or ->overload()  to rewrite existing "setEnv" values!
    } else {
        throw new Exception();
    }
});
$di->get('env');

/**
 * Set debugger for  development environment
 */
if (APP_ENV === 'dev') {
    (new \Phalcon\Debug())->listen();
}

/**
 * The URL component, to support all urls in the Phalcon
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});


/**
 * Crypt service
 */
$di->set('crypt', function () use ($config) {
    $salt = $_ENV['SALT'] ?? '!@#$%^&*()_+!@#$%^&*()_+!@#$%^&*()_+' ;
    return (new Crypt())->setKey($salt);
});


/**
 * Session component from Session/Adapter/Files
 */
$di->setShared('session', function () {
        $session = new AdapterFiles();
        $session->start();

        return $session;
    }
);


/**
 * Database connection is created based in the parameters defined in the configuration file
 * Simple sqlite database connection to local file
 */
$di->setShared('db', function () {
    return new \Phalcon\Db\Adapter\Pdo\Sqlite([
        "dbname" => '/tmp/db/'. $_ENV['DB_NAME']
    ]);
});


/**
 * Setting namespace of controllers
 *
 * Dispatching is the process of taking the request object, extracting the module name,
 * controller name, action name, and optional parameters contained in it, and then
 * instantiating a controller and calling an action of that controller.
 */
//$di->set('dispatcher', function () {
//
//    $exceptionPlugin = new \app\plugins\ExceptionPlugin();
//    $exceptionPlugin->setDI($this);
//
//    /** @var \Phalcon\Events\Manager $eventsManager */
//    $eventsManager = $this->getEventsManager();
//    $eventsManager->attach('dispatch', $exceptionPlugin);
//
//    $dispatcher = new Dispatcher();
//    $dispatcher->setEventsManager($eventsManager);
//    $dispatcher->setDefaultNamespace('App\Controllers');
//
//    return $dispatcher;
//});


/**
 * Register a ClientRequest from incubator/library
 *
 * @see vendor/phalcon/incubator/Library/Phalcon/Http/Client/README.md
 */
//$di->set(ClientRequest::class, function () {
//    return ClientRequest::getProvider();
//});
