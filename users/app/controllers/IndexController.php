<?php

namespace App\Controllers;

use app\plugins\JsonHelper;

class IndexController extends ControllerBase
{

    /**
     * Index action
     * stub for test and for handling errors
     */
    public function indexAction()
    {
        if ($this->request->isGet()) {

            // default stub, like welcome string
            //return 'Welcome to jsonRPC API';
            JsonHelper::returnRpcResponse('Welcome to GETjsonRPC API');
            die;

        } else {
            JsonHelper::returnRpcResponse('Welcome to POSTjsonRPC API');
            die;
        }

    }

    /**
     * Home action and test for request
     * {"jsonrpc":"2.0","method":"index/home","params":"test","id":1}
     */
    public function HomeAction()
    {

        JsonHelper::returnRpcResponse('BooYa !');
        die;
    }
}
