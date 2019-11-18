<?php

namespace App\Controllers;

class ErrorController extends ControllerBase
{
    /**
     * Default not found action
     * @return \Phalcon\Http\Response
     */
    public function show404Action()
    {
        $this->log()->error('Got 404!');

        return $this->view->render('error', '404.volt');
        die();
    }

    /**
     * Default internal server error action
     * @return \Phalcon\Http\Response
     */
    public function unhandledExceptionAction()
    {
        $this->log()->error('Got unhandledException');

        return $this->view->render('error', '500.volt');
        die();
    }

    /**
     * Default unknown error
     * @return \Phalcon\Http\Response
     */
    public function indexAction()
    {
        $this->log()->warning('WAT???');

        return $this->view->setContent('simple, unknown error !');
        die();
    }

}
