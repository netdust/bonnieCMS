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
        $this->data_array = ( array ) $this->app->config('theme');
    }

    public function post(  )
    {
        $request = (array) json_decode($this->app->request()->getBody());

        foreach( $request as $key => $val ) {
            if( array_key_exists( $key, $this->data_array ) ) {
                $this->app->config('theme')->{$key} = $val;
            }
        }

        file_put_contents(__ROOT__.$this->file, json_encode($this->app->config('theme')));
        $this->render( 200, $this->data_array );

    }

}