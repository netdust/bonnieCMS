<?php

namespace api\Controller;

class PagemetaController extends \api\Controller\Controller
{
    public function get( $id=0, $type='page', $param=array()  ) // output json
    {
        $this->render( 200, $this->_get( $id, 'pageMeta', $param ) );
    }

    public function delete( $id ) {
        if( !is_null( $id ) ) {
            $r = \Model::factory('pageMeta')->find_one( $id );
            if($r) $r->remove();
        }
        $this->render( 200, array('status'=>'success') );
    }
}