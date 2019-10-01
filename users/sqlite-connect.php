<?php

namespace AppUsers;

use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Mvc\Micro;

class Connect
{

    public function __construct()
    {
        $di->set('db', function(){
            return new Sqlite([
                "dbname" => '/tmp/db/test.db'
            ]);
        });
    }

    public function sqlite()
    {
        $di->set('db', function(){
            return new \Phalcon\Db\Adapter\Pdo\Sqlite([
                "dbname" => '/tmp/db/test.db'
            ]);
        });


        try {
            $result = $di->query('select * from content')->fetch();
            var_dump($result);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

}