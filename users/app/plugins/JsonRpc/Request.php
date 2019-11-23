<?php

namespace app\plugins\JsonRpc;

use Phalcon\Http\Request as PhalconRequest;

class Request extends PhalconRequest
{
    /**
     * Rpc requests
     * @var Request[]
     */
    protected $rpcRequests = [];

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        // creates rpc requests collection from raw body
        // and adds them to $this->rpcRequests
        $this->rpcRequests[] = $this->getRawBody();
    }
//
//    /**
//     * Gets a variable from put request
//     *
//     * <code>
//     * // Returns value from $_PUT["user_email"] without sanitizing
//     * $userEmail = $request->getPut("user_email");
//     *
//     * // Returns value from $_PUT["user_email"] with sanitizing
//     * $userEmail = $request->getPut("user_email", "email");
//     * </code>
//     *
//     * @param string $name
//     * @param mixed $filters
//     * @param mixed $defaultValue
//     * @param bool $notAllowEmpty
//     * @param bool $noRecursive
//     * @return mixed
//     */
//    public function getPut(
//        $name = null,
//        $filters = null,
//        $defaultValue = null,
//        $notAllowEmpty = false,
//        $noRecursive = false
//    ) {
//        if (strpos($this->getContentType(), 'json') !== false) {
//            $put = $this->getJsonRawBody(true);
//            if (gettype($put) != 'array') {
//                $put = [];
//            }
//            $this->_putCache = $put;
//        }
//
//        return parent::getPut($name, $filters, $defaultValue, $notAllowEmpty,
//            $noRecursive);
//    }
//
//    /**
//     * get data from Body and $_REQUEST
//     * Gets a variable from the $_REQUEST superglobal applying filters if needed.
//     * If no parameters are given the $_REQUEST superglobal is returned
//     *
//     * <code>
//     * // Returns value from $_REQUEST["user_email"] without sanitizing
//     * $userEmail = $request->get("user_email");
//     *
//     * // Returns value from $_REQUEST["user_email"] with sanitizing
//     * $userEmail = $request->get("user_email", "email");
//     * </code>
//     *
//     * @param string $name
//     * @param mixed $filters
//     * @param mixed $defaultValue
//     * @param bool $notAllowEmpty
//     * @param bool $noRecursive
//     * @return mixed
//     */
//    public function get($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false) {
//        $res = null;
//
//        if (strpos($this->getContentType(), 'json') !== false) {
//            $res = self::getPut($name, $filters, null, $notAllowEmpty, $noRecursive);
//        }
//
//        if ($res === null) {
//            $res = parent::get($name, $filters, null, $notAllowEmpty, $noRecursive);
//        }
//
//        if ($res === null) {
//            $res = $defaultValue;
//        }
//
//        return $res;
//    }

}
