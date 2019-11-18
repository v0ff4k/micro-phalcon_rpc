<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Crypt;
use Phalcon\Flash\Direct;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as AdapterFiles;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

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
        $env = new Dotenv\Dotenv(dirname(realpath($envFile)));
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
 * Set the view(all available) from phalcon/incubator
 */
$di->setShared('view', function () use ($config) {
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    $view->registerEngines([
        //default
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php',
//        '.volt' => 'Phalcon\Mvc\View\Engine\Volt',
        '.volt' => function ($view) use ($config) {
            $volt = new Phalcon\Mvc\View\Engine\Volt($view, $this);
            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledExtension' => '.compiled',
                'lifetime' => 86400,
                'compileAlways' => true,
                'compiledSeparator' => '_'
            ]);

            //custom function update.
            if(class_exists('\Symfony\Component\VarDumper\VarDumper')) {
                $volt->getCompiler()->addFunction(
                    'dumps',
                    function (...$params) {
                        $params = ($params) ? (( count($params) <= 1 ) ? $params[0] : $params) : '';

                        return \Symfony\Component\VarDumper\VarDumper::dump($params);
                    }
                );
            }

            if(class_exists('\Symfony\Component\Debug\Debug')) {
                $volt->getCompiler()->addFunction(
                    'debug',
                    function ($level = E_ALL, $showErrs = true) {
                        return \Symfony\Component\Debug\Debug::enable($level, $showErrs);
                    }
                );
            }

            return $volt;
        },

// too fat for  `composer require twig/twig` used from incubator
//        '.twig' => 'Phalcon\Mvc\View\Engine\Twig',
//        '.mustache' => 'Phalcon\Mvc\View\Engine\Mustache',
    ]);

    return $view;
});


/**
 * Crypt service
 */
$di->set('crypt', function () use ($config) {
    $salt = $_ENV['SALT'] ?? '!@#$%^&*()_+!@#$%^&*()_+!@#$%^&*()_+' ;
    return (new Crypt())
        ->setKey($salt);
});


/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return (new Direct([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]))->setAutomaticHtml(true);
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
    $eventsManager = $this->getEventsManager();
    $eventsManager->attach('dispatch', $exceptionPlugin);

    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    $dispatcher->setDefaultNamespace('App\Controllers');

    return $dispatcher;
});


/**
 * Logger service
 */
$di->setShared('logger', function ($filename = null, $format = null) use ($config) {
    //in critical log as  (new \app\plugins\Logger())->error($msg);

    //same:  $config->get('logger')->format  === $config->logger->format
    $cLogger = $config->logger;

    $format = $format ?: $cLogger->format;
    $filename = trim($filename ?: $cLogger->filename, '\\/');
    $path = rtrim($cLogger->path, '\\/') . DIRECTORY_SEPARATOR;

    $formatter = new LineFormatter($format, $cLogger->date);
    $logger = new FileAdapter($path . $filename);

    $logger->setFormatter($formatter);
    $logger->setLogLevel($cLogger->logLevel);

    return $logger;
});

/**
 * Register a ClientRequest from incubator/library
 * Simple curl() replacer, send post/get/patch to remote serv
 *
 * @see vendor/phalcon/incubator/Library/Phalcon/Http/Client/README.md
 * @see vendor/phalcon/incubator/Library/Phalcon/Http/Client/Provider/Curl
 * @see vendor/phalcon/incubator/Library/Phalcon/Http/Client/Provider/Stream
 */
$di->set('ClientRequest', function () {
    return \Phalcon\Http\Client\Request::getProvider();
});
