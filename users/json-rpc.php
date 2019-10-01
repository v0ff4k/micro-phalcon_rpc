<?php

$response = new \Phalcon\Http\Response();

$response->setJsonContent([]);

//default  diplay json request for posting also can be  as a test !
$r = new JsonRPCRouter();
$r->handle(
    '{"jsonrpc": "2.0","method": "user.login","params": {"login" : "admin","password" : "admin"},"id":1}'
);

$response->send();