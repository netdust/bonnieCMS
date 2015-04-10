<?php

namespace api\Controller;

class ConfigController extends \api\Controller\JsonController
{

    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();
        $this->init_data();
    }

    protected function init_data( $key='' ) {
        $this->load();
        $this->data_array = $this->app->config('theme');
    }

}