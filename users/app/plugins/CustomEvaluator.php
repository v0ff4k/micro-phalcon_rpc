<?php

namespace app\plugins;

use app\plugins\Logger;
use Phalcon\DispatcherInterface;
use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exceptions\ApplicationException;

/**
 * Class CustomEvaluator
 * @package app\plugins
 */
class CustomEvaluator  implements Evaluator
{
    /** @var DispatcherInterface */
    private $dispatcher;

    /**
     * CustomEvaluator constructor.
     * @param \Phalcon\DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $method
     * @param $arguments
     * @return array|mixed
     */
    public function evaluate($method, $arguments)
    {
        $controllerWithMethod = $this->getControllerAndAction($method);

        $this->dispatcher->forward($controllerWithMethod);
        $this->dispatcher->setParams($arguments);

        try {
            $this->dispatcher->dispatch();
        } catch (\Exception $e) {

            try {
                (new Logger())->error(
                    '[message: ]' . $e->getMessage() .
                    ',[code:]' . $e->getCode()
                );
            } catch (\Exception $e) {
              return 'Dooh, All fails';
            }
            return ['Unable evaluate your request'];
        }
        return $this->dispatcher->getReturnedValue();
    }

    /**
     * Just
     * @param string $method
     * @return array
     */
    private function getControllerAndAction(string $method)
    {
        $method = str_replace(['Controller', 'Action'], ['', ''], $method);

        $methodAndController = explode('/', $method);
        if (count($methodAndController) !== 2) {
            (new Logger())->error('bad $method, check: ' . json_encode( $method));
            throw \Exception('controller/action');
        }

        $controller = 'app\\controllers\\' . ucfirst($methodAndController[0]);
        $action = $methodAndController[1];

        if(
            class_exists($controller . 'Controller') &&
            method_exists($controller . 'Controller', $action . 'Action')
        ) {
            (new Logger())->info('find ' . $controller . 'Controller->' . $action . 'Action');
            return [
                'controller' => $controller,
                'action' => $action,
            ];
        } else {
            (new Logger())->debug(
                'Try to reach not existing class/method: ' . $controller . '->' . $action
            );
            return [
                'controller' => 'error',
                'action'     => 'unhandledException'
            ];
        }
    }
}
