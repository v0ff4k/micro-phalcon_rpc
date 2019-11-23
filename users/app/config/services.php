<?php

/**
 * Register all available services
 * like services.yml ;)
 * @var Phalcon\Config $config
 * @var Phalcon\Di $di
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
    $url = new Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});


/**
 * Crypt service
 */
$di->set('crypt', function () use ($config) {
    $salt = $_ENV['SALT'] ?? '!@#$%^&*()_+!@#$%^&*()_+!@#$%^&*()_+' ;
    return (new Phalcon\Crypt())->setKey($salt);
});


/**
 * Session component from Session/Adapter/Files
 */
$di->setShared('session', function () {
        $session = new Phalcon\Session\Adapter\Files();
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


$di->set('request', function () {
    $request = new \app\plugins\JsonRpc\Request();

    return $request;
});

//Router inject with  catch json body and send to proper controller/method
$di->set('router', function () {

    /** @var app\plugins\JsonRpc\Request $request */
    $request = $this->get('request');
    $router = new app\plugins\JsonRpc\Router($request->getRawBody());

    return $router;
});


/**
 * Reg jsonRpc Response
 */
$di->setShared('response', 'app\plugins\JsonRpc\Response');


/**
 * Disable any view output to client.
 */
$di->setShared('view', function () {
    $view = new Phalcon\Mvc\View();
    $view->disable();

    return $view;
});

/**
 * Setting namespace of controllers
 *
 * Dispatching is the process of taking the request object, extracting the module name,
 * controller name, action name, and optional parameters contained in it, and then
 * instantiating a controller and calling an action of that controller.
 */
$di->set('dispatcher', function () {

    $exceptionPlugin = new \app\plugins\ExceptionPlugin();
    $exceptionPlugin->setDI($this);

    /** @var \Phalcon\Events\Manager $eventsManager */
    $eventsManager = new \Phalcon\Events\Manager();
    $eventsManager->attach('dispatch', $exceptionPlugin);

    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    $dispatcher->setDefaultNamespace('App\Controllers');


    return $dispatcher;
});
