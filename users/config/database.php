<?php

//todo  just  get config and return as phpfpm var!


namespace AppUsers\config;

use Dotenv\Dotenv;
use \Phalcon\Db\Adapter\Pdo\Sqlite;
use \Phalcon\Mvc\Collection\Manager;


//Load configs
$dotenv = new Dotenv(__DIR__ . '../../', 'variables.env');
$dotenv->load();
$dotenv->required(['DB_TYPE', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']);

$dbType = getenv('DB_TYPE');
$dbType = $_ENV['DB_TYPE'];
$dbType = $_SERVER['DB_TYPE'];


/** $di \Phalcon\DI\FactoryDefault */

// Simple sqlite3 database connection to localhost
//Registers a service in the services container
$di->set('db', function () {// was mongo
    return new Sqlite([
        "dbname" => '/tmp/db/test.db'
    ]);
}, true);

$di->set('collectionManager', function(){
    return new Manager();
}, true);