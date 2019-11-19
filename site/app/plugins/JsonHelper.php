<?php

namespace app\plugins;

use Phalcon\Http\Response;

class JsonHelper
{

    /**
     * Micro helper for send response directly in JSON format
     *
     * @param bool $status
     * @param null $message
     * @param array $dataArray
     * @param $statusCode
     * @return void
     */
    public static function returnJson($status = true, $message = null, $dataArray = [], $statusCode)
    {

        if (method_exists($message, 'getMessage')) {
            $message = $message->getMessage();
        }

        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $statusCode = true === $status ? 200 : $statusCode;
        $response->setStatusCode($statusCode);

        $response->setJsonContent(
            [
                'status' => true === $status ? 'success' : 'error',
                'message' => $message,
                'data' => $dataArray,
            ]
        );

        if (true !== $response->isSent()) {
            $response->send();
        }
    }

    /**
     * Generate simple request in json-rpc format
     *
     * @param string $method  Example: SomeController/nameAction || some/name || some
     * @param array|string|int $params  Ex: 'bar' | "foo" | 1 | ['foo'=>'bar', 'id'=>1]
     * @param int $id
     * @param int $statusCode
     * @return string|array
     */
    public static function simpleJsonRpcRequest(string $method, $params = [], $id = 1, $asArray = false)
    {
        $data = [
            'jsonrpc' => "2.0",
            'method' => $method,
            'id' => (integer)$id
        ];
        if (isset($params)) $data['params'] = $params;

        if (false === $asArray) {
            return json_encode($data);
        }

        return $data;

        // '{"jsonrpc": "2.0","method": "user/login","params": {"login" : "admin","password" : "admin"},"id":1}'
    }

    /**
     * Generate simple Response in json-rpc format
     *
     * @param array|string $body
     * @param bool $isError
     * @param int|mixed $code  see
     * @param int $id
     * @param bool $asArray
     * @return array|false|string
     */
    public static function simpleJsonRpcResponse($body = [], $isError = false, $code = 0, $id = 1, $asArray = false)
    {
        $data = [
            'jsonrpc' => "2.0",
            'id' => (integer)$id
        ];
        if (true === $isError) {
            if(is_string($body)) {
                $data['error']['code'] = $code;
                $data['error']['message'] = $body;
            }elseif (is_array($body) && isset($body['message'])) {
                $data['error']['message'] = $body['message'];
                $data['error']['code'] = $code;
            }else{
                //but if body empty return message as humanizeJsonError(-32700..-32000)
                if(!$body) {
                    $data['error']['message'] = self::humanizeJsonError($code);
                    $data['error']['code'] = $code;
                } else {
                    $data['error'] = $body;
                }
            }
        } else {
            $data['result'] = $body;
        }

        if (false === $asArray) {
            return json_encode($data);
        }

        return $data;

        // {"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}
        // '{"jsonrpc": "2.0","result": {"login" : "admin","id" : "1"},"id":1}'
    }

    /**
     * @param $method
     * @param array|string|int $params
     * @param int $id
     * @param int $statusCode
     * @return void|Response
     */
    public static function returnJsonRpcRequest(string $method, $params = [], $id = 1, $statusCode = 200)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        $response->setStatusCode($statusCode);
        $response->setJsonContent(self::simpleJsonRpcRequest($method, $params, $id, true));

        // '{"jsonrpc": "2.0","method": "user/login","params": {"login" : "admin","password" : "admin"},"id":1}'
        if (true !== $response->isSent()) {
            $response->send();
        }
    }

    /**
     * Send response to browser directly converted jsonRPC.
     *
     * @param string|array $result
     * @param bool $isError
     * @param int $code
     * @param int $id
     * @param int $httpStatusCode
     * @return void|Response
     */
    public static function returnJsonRpcResponse($result = [], $isError = false, $code = 0, $id = 1, $httpStatusCode = 200)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        $response->setStatusCode($httpStatusCode);
//        $response->setJsonContent([
//            'jsonrpc' => "2.0",
//            'result' => $result,
//            'id' => (integer)$id
//        ]);
        $response->setJsonContent(self::simpleJsonRpcResponse($result, $isError, $code, $id, true));

        // '{"jsonrpc": "2.0","result": {"login" : "admin","token" : "blabla123"},"id":1}'
        if (true !== $response->isSent()) {
            $response->send();
        }
    }

    /**
     * Integer to human converter, from json_last_error()
     *
     * @param integer $jsonLastError
     * @return string
     */
    public static function humanizeError(int $jsonLastError)
    {
        $error = '';

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = 'No errors';
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error = 'Unknown error';
                break;
        }

        return $error;
    }

    /**
     * Integer to human converter, from json RPC()
     *
     * @see http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php
     * @param integer|mixed $code  Also can be integer, auto convert to negative and check
     * @return bool|string
     */
    public static function humanizeJsonError($code)
    {
        if($code >= 32000 || $code <= 32700) {
            $code = -$code;
        }elseif($code > -32000 || $code < -32700) {
            return false;
        }

        //if problem appears with negative numbers convert to abc and use (int)
        switch ($code) {
            case -32700:
                $error = 'Parse error';//Invalid JSON was received by the server.
                //An error occurred on the server while parsing the JSON text.
                break;
            case -32600:
                $error = 'Invalid Request';//The JSON sent is not a valid Request object.
                break;
            case -32601:
                $error = 'Method not found';//The method does not exist / is not available.
                break;
            case -32602:
                $error = 'Invalid params';//Invalid method parameter(s).
                break;
            case -32603:
                $error = 'Internal error';//Internal JSON-RPC error.
                break;
            case -32000:
            case -32099:
            default:
                $error = 'Server error';//Reserved for implementation-defined server-errors.
                break;
        }

        return $error;
    }

}
