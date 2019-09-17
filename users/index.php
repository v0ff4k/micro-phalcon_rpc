<?php

use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Mvc\Micro;

$app['db'] = function () {
    return new Sqlite([
        "host" => "localhost",
        "username" => "root",
        "password" => "root",
        "dbname" => "phalcon_db"
    ]);
};


$app = new Micro();

$app->get(
    '/login',
    function () use ($app) {
        $user = $app['db']->query('SELECT id FROM users WHERE login=:login AND password=:password');

        return (isset($user) ? 1 : 0);
    }
);
