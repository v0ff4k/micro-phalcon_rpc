<?php

namespace app\plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class ExceptionPlugin extends Plugin
{
    /**
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @param \Exception|mixed $exception   or some similar to popular \Exception
     * @return bool
     * @throws \Exception
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $method = $dispatcher->getActiveMethod();

        (new Logger())->debug(
            'Exception plugin raised by controller: ' . $controller .
            ', action: ' . $action .
            ', method: ' . $method .
            ', with code:' . $exception->getCode())
        ;

        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward([
                    'controller' => 'error',
                    'action'     => 'show404'
                    ]);

                //return to App\Controllers\ErrorController:show404Action
                return false;
            break;
        }
        $dispatcher->forward([
            'controller' => 'error',
            'action'     => 'unhandledException'
        ]);

        //return to App\Controllers\ErrorController:unhandledExceptionAction
        return false;
    }
}