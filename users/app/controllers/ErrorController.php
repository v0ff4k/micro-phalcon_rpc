<?php

namespace App\Controllers;

use app\plugins\JsonHelper;

class ErrorController extends ControllerBase
{
    /**
     * Default not found action
     * @return \Phalcon\Http\Response
     */
    public function show404Action()
    {
        $this->log()->error('Got 404!');

        JsonHelper::returnRpcResponse([], true, -32601, 1, 404);
        die();
    }

    /**
     * Default internal server error action
     * @return \Phalcon\Http\Response
     */
    public function unhandledExceptionAction()
    {
        $this->log()->error('Got unhandledException');
        JsonHelper::returnRpcResponse([], true, -32601, 1, 500);
        die();
    }

    /**
     * Default unknown error
     * @return \Phalcon\Http\Response
     */
    public function indexAction()
    {
        $this->log()->warning('WAT???');
        JsonHelper::returnRpcResponse(['message' => 'Unknown error'], true, -32601, 1, 404);
        die();
    }

}
