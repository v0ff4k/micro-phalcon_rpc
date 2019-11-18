<?php

use Phalcon\Mvc\Micro;


function loginForm () {

    echo "<h1>Insert your correct credentials!</h1><hr noshade />";
    echo "<form method='post'>
<input type='text' />&nbsp;<input type='password' />&nbsp;<input type='submit' />
</form>";

}


try {

    require_once __DIR__ . '../vendor/autoload.php';

    $app = new Micro();

    // GET  /  route for display welcome screen + links
    $app->get(
        "/",
        function () use ($app) {
            //gen link from name
            $loginUrl = $app->url->get(['for' => 'login-post']);

            //display welcome site
            echo "<h1>welcome !</h1>you can <a href='".$loginUrl."'>login here</a>";
        }
    );

    // GET  /login  route for display form
    $app->get("/login", 'loginForm');

    // POST  /login  route
    $app->post(
        "/login",
        function () use ($app) {

            //send request to user/index.phpfpm  find user and return success

            //exchange with  json-rpcrpc
            echo "<h1>Successful auth!</h1>";
        }
    )->setName('login-post')
    ;

    // GET  /logout  route
    $app->get('/logout',
        function () use ($app) {
            $app->response->redirect('/');
            $app->response->sendHeaders();
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



} catch (\Exception $e) {

    return ResponseJson::getJson(0, 'There was an error processing your request', $e->getMessage(), 400);
}