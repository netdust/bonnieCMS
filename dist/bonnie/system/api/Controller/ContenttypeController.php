<?php

namespace api\Controller;

class ContenttypeController extends \api\Controller\JsonController
{

    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();
        $this->init_data('contenttypes');
    }

}