<?php

namespace app\plugins\JsonRpc;

use Phalcon\Http\Response as PhalconResponse;

class Response extends PhalconResponse
{
    /**
     * Request id
     * @var string|int|null
     */
    public $id;

    /**
     * Request version
     * @var string
     */
    public $version = '2.0';

    /**
     * Method execution result
     * @var string
     */
    public $result;

    /**
     * Error occurred while executing
     * JsonRPC request
     * @var \Exception
     */
    public $error;

    /**
     * Returns string representation
     * @return string
     */
    public function getContent()
    {
        $response = [
            'id'      => $this->id,
            'jsonrpc' => $this->version
        ];

        //Use the current content
        $result = parent::getContent();

        if (isset($this->error)) {
            $response['error'] = [
                'code'    => $this->error->getCode(),
                'message' => $this->error->getMessage(),
            ];
        } else {
            $response['result'] = $result;
        }

        return json_encode($response);
    }
}
