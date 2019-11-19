<?php

namespace App\Controllers;

use app\plugins\CustomEvaluator;
use app\plugins\JsonHelper;
use Datto\JsonRpc\Server;
use Datto\JsonRpc\Client;

class IndexController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        if ($this->request->isGet()) {
            // default stub, like welcome string
            //return 'Welcome to jsonRPC API';
            JsonHelper::returnJsonRpcResponse('Welcome to jsonRPC API');
            die;

        } else {
            // simple DattoServer with custom Evaluator
            $api = new CustomEvaluator($this->dispatcher);
            $server = new Server($api);

            return $server->reply($this->request->getRawBody());
        }
    }
}
