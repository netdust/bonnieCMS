<?php

namespace api\Controller;

class Controller
{
    protected $app;

    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();
    }

    protected function _get( $id, $type='page', $param=array() )
    {
        $this->app->applyHook( $type.'.get', $id, $param );

        if( !$id ) $arr = \Model::factory($type)->find_array();
        else {
            $arr = \Model::factory($type)->find_one( $id )->as_array();
        }

        return $arr;
    }

    public function get( $id=0, $type='page', $param=array()  ) // output json
    {
        $this->render( 200, $this->_get( $id, $type, $param ) );
    }

    public function render( $template, $data = array(), $status = null ) {
        $this->app->applyHook('before.render');
        $this->app->render( $template, $data, $status );
        $this->app->applyHook('after.render');
    }

}