<?php

namespace helpers;

use \Phalcon\Http\Response;

class ResponseJson
{

    /**
     * Micro helper for response in JSON format
     *
     * @param bool $status
     * @param null $message
     * @param array $dataArray
     * @param $statusCode
     */
    public static function getJson($status = true, $message = null, $dataArray = [], $statusCode)
    {

        if (method_exists($message,'getMessage')) {
            $message = $message->getMessage();
        }

        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $statusCode = true === $status ? 200 : $statusCode;
        $response->setStatusCode($statusCode);

        $response->setJsonContent([
            'status' => true === $status ? 'success' : 'error',
            'msg' => $message,
            'data' => $dataArray,
        ]);
        // '{"jsonrpc": "2.0","method": "user.login","params": {"login" : "admin","password" : "admin"},"id":1}'

        $response->send();
    }
}
