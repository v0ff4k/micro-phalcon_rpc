<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller as PhalconController;

/**
 * Class ControllerBase - a.k.a. DefaultController in phalcon.
 *
 * @property \Phalcon\Logger\Adapter\File $logger
 * @package App\Controllers
 */
class ControllerBase extends PhalconController
{

    /**
     * Resolves the service based on its configuration
     *
     * @param string $name
     * @param array|null|mixed $parameters
     * @return mixed
     */
    protected function getDiContainer($name, $parameters = null)
    {
        return $this->getDI()->get($name, $parameters);
    }

    /**
     * Micro method to simplify log system.
     *
     * @return \app\plugins\Logger
     */
    protected function log()
    {
        try {
            return new \app\plugins\Logger();
        } catch (\Exception $e) {
            return null;//todo
        }
    }

    /**
     * Builds success responses.
     */
    public function buildSuccessResponse($code, $messages, $data = '')
    {
        switch ($code) {
            case 200:
                $status = 'OK';
                break;
            case 201:
                $status = 'Created';
                break;
            case 202:
                break;
        }
        $generated = array(
            'status' => $status,
            'code' => $code,
            'messages' => $messages,
            'data' => $data,
        );
        $this->response->setStatusCode($code, $status)->sendHeaders();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent($generated, JSON_NUMERIC_CHECK)->send();
        die();
    }

    /**
     * Builds error responses.
     */
    public function buildErrorResponse($code, $messages, $data = '')
    {
        switch ($code) {
            case 400:
                $status = 'Bad Request';
                break;
            case 401:
                $status = 'Unauthorized';
                break;
            case 403:
                $status = 'Forbidden';
                break;
            case 404:
                $status = 'Not Found';
                break;
            case 409:
                $status = 'Conflict';
                break;
        }
        $generated = array(
            'status' => $status,
            'code' => $code,
            'messages' => $messages,
            'data' => $data,
        );
        $this->response->setStatusCode($code, $status)->sendHeaders();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent($generated, JSON_NUMERIC_CHECK)->send();
        die();
    }
}
