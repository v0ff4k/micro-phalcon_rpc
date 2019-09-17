<?php

$di->set('db', function(){
    return new \Phalcon\Db\Adapter\Pdo\Sqlite([
        "dbname" => 'db/test.db'
    ]);
});