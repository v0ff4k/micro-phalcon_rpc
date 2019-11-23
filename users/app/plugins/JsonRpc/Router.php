<?php

namespace app\plugins\JsonRpc;

use Phalcon\Mvc\Router as PhalconRouter;

/**
 * Class JsonRpcRouter
 * @package plugins
 */
class Router extends PhalconRouter
{
    protected $_data;

    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @param null $data
     * @throws \Phalcon\Mvc\Router\Exception
     */
    public function handle($data = null)
    {
        if (!$data && $this->_data) {
            $data = $this->_data;
        }

        if (!$data) {
            $data = '{"jsonrpc":"2.0","method":"index/index","params":"","id":1}';
        }

        $data = json_decode($data, true);

        if (!isset($data['jsonrpc'])) {
            throw new \Phalcon\Mvc\Router\Exception("The request is not Json-RPC");
        }

        // @todo  'dot' or 'slash' will separate class-method?
        $method = explode('/', $data['method']);

        $this->_controller = (string)$method[0];
        $this->_action = (string)$method[1];
        $this->_params = (array)$data['params'];
    }

}
