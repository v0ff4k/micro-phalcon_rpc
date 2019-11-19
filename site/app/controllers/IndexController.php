<?php

namespace App\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        //
    }

    /**
     * Home action
     *
     * return bool|\Phalcon\Mvc\View
     */
    public function homeAction()
    {
        return $this->view->render('index/home.volt', 'index');
    }

}
