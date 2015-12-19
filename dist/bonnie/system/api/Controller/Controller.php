<?php

namespace api\Controller;

class Controller
{
    protected $app;

    protected $writeData = 1;
    protected $readData = 2;
    protected $deleteData = 4;
    protected $addUser = 8;
    protected $deleteUser = 16;

    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();

        $this->root = $this->writeData | $this->readData | $this->deleteData | $this->addUser | $this->deleteUser;
        $this->powerUser = $this->readData | $this->deleteData | $this->deleteUser;
        $this->user = $this->writeData | $this->readData;
        $this->guestUser = $this->readData;
    }

    /*
    public function allowed( $user, $role='admin' ) {
        if(checkRights($role, $addUser)) {
            //do some action
            return TRUE;
        }

        return FALSE;
    }
    */

    protected function checkRights($user, $permission) {
        if($user & $permission) {
            return true;
        } else {
            return false;
        }
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