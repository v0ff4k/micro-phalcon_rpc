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
//$index->setHandler(new app\Controllers\IndexController(), true);
// Gets index
$index->get('/', 'indexAction');
$index->post('/', 'indexAction');
$app->mount($index);

//others controllers will be trigged from jsonrpc
// {"jsonrpc": "2.0", "method": "controller/method", "params": { "a": 1, "b": 2 }, "id": 1}

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {

    (new \app\plugins\Logger())->debug('We got 404!');

    $app->response
        ->setStatusCode(404, "Not Found")
        ->setContentType('application/json', 'UTF-8')
        ->setJsonContent(\app\plugins\JsonHelper::rpcResponse('URL Not found', true, -32601, true));

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

    /** @var \Exception $exception  Or smth similar to \Exception */
    $msg = $exception->getMessage() . PHP_EOL .
        '[Stack Trace]' . PHP_EOL .
        $exception->getTraceAsString();
    (new \app\plugins\Logger())->error($msg);

        if (APP_ENV === 'dev') {
            print_r($exception->getMessage() . '<br />');
            print_r('<pre>' . $exception->getTraceAsString() . '</pre>');
        } else {
            print_r('An error has occurred');
        }
    }
);

$app->handle();