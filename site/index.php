<?php

use Phalcon\Mvc\Micro;


function loginForm () {

    echo "<h1>Welcome!</h1>";
    echo "<form action='/login' method='post'>
<input type='text' /><input type='password' /><input type='submit' />
</form>";

}


$app = new Micro();

$app->get("/", 'loginForm');

$app->get('/login',
    function () use ($app) {
        $app->response->redirect('/');
        $app->response->sendHeaders();
    }
);

$app->post(
    "/login",
    function () use ($app) {

        //send request to user/index.php  find user and return success

        //exchange with  json-rpcrpc
        echo "<h1>Successful auth!</h1>";
    }
);

$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, "Not Found");
        $app->response->sendHeaders();
        echo "This page was not found!";
    }
);

$app->handle();