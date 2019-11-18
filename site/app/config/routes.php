<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;

/**
 * List of all routes
 * like routes.yml
 *
 *  often used  as  example.com/class/method
 *  where  ClassController{ public indexAction(); public methodAction(); }
 *
 * @var Phalcon\Di\FactoryDefault $di
 * @var Phalcon\Mvc\Micro $app
 */


$index = new MicroCollection();
$index->setHandler('App\\Controllers\\IndexController', true);
// Gets index
$index->get('/', 'homeAction');
$app->mount($index);


$user = new MicroCollection();
$user->setHandler('App\\Controllers\\UserController', true);
$user->mapVia('/login', 'loginAction', ['GET','POST',]);

//// Login form for user
//$user->get('/login', 'loginAction');
//// Authenticates a user
//$user->post('/login', 'loginAction');
// logout
$user->get('/logout', 'logout');
// Adds index routes to $app
$app->mount($user);

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {

    $app->response
        ->setStatusCode(404, "Not Found")
        ->sendHeaders()
        ->setContent($app->view->render('error/404.volt', 'show404'));
    if (true !== $app->response->isSent()) {
        //do not send headers if headers already printed!
        $app->response->send();
    }

});


/**
 * Error handler
 * @var \Exception $exception
 */
$app->error(function ($exception) {

    $msg = $exception->getMessage() . PHP_EOL .
        '[Stack Trace]' . PHP_EOL .
        $exception->getTraceAsString();
    (new \app\plugins\Logger())->error($msg);


    /** @var \Exception $exception */
        if (APP_ENV === 'dev') {
            print_r($exception->getMessage() . '<br />');
            print_r('<pre>' . $exception->getTraceAsString() . '</pre>');
        } else {
            print_r('An error has occurred');
        }
    }
);

$app->handle();