<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller as PhalconController;

/**
 * Class ControllerBase
 * Stupid DefaultController in phalcon names.
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
}
