<?php

namespace AppUsers;

use helpers\ResponseJson;
use \Phalcon\Mvc\Micro;
use \Phalcon\DI\FactoryDefault;
use \Phalcon\Loader;


try {

    //1 Initialize Dependency Injection
    $di = new FactoryDefault();

//    $di->set(
//        'config',
//        function () {
//            return new Ini('config.ini');
//        }
//    );

    //2 use Loader  as register Directories
    $loader = new Loader();

    $loader->registerDirs([
            __DIR__ . '/library/',
            __DIR__ . '/models/'
        ]
    )->register();

    //3 Use composer autoloader to load vendor classes
    require_once __DIR__ . '../vendor/autoload.php';

    //4 Initialize DB  replace with build in  config get from variables.env file !!!!
    include_once(__DIR__ . '/config/database.php');

    //5 Start Micro() as the app
    $app = new Micro();

    $app->post(
        '/login',
        function () use ($app) {

            //$user = $app['db']->query('SELECT id FROM users WHERE login=:login AND password=:password');
            return (isset($user) ? 1 : 0);
        }
    );


// Not Found
    $app->notFound(function () use ($app) {

        $app->response->setStatusCode(404, 'Not Found');
        $app->response->sendHeaders();

        $message = 'Nothing to see here. Move along....';
        $app->response->setContent($message);
        $app->response->send();
    });

// Default Response
    $app->get('/', function () {
        return 'API is ready!';
    });

//Add any filter before running the route
    $app->before(function () use ($app) {
        //You may want to add some basic auth in order to access the REST API
    });

//This is executed after running the route
    $app->after(function () use ($app) {

    });


//needed ???
    $app->handle();



} catch (\Exception $e) {

    return ResponseJson::getJson(0, 'There was an error processing your request', $e->getMessage(), 400);
}


function jsonResponce()
{

}




