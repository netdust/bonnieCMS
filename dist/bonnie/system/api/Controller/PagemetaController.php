<?php

namespace api\Controller;

class PagemetaController extends \api\Controller\Controller
{
    public function get( $id=0, $type='page', $param=array()  ) // output json
    {
        $this->render( 200, $this->_get( $id, 'pageMeta', $param ) );
    }

    public function sort( $id=0, $tid=0, $param=array()  ) {
        $this->app->applyHook( 'pagemeta.sort', $id, $tid );
        $request = (array) json_decode($this->app->request()->getBody());

        foreach( $request as $item ) {
            $r = \Model::factory('pageMeta')->find_one( $item->id );
              if( $r ) {
                $r->sort = $item->sort;
                $r->save();
            }
        }
        $this->render( 200, $request );
    }

    public function delete( $id ) {
        if( !is_null( $id ) ) {
            $r = \Model::factory('pageMeta')->find_one( $id );
            if($r) $r->remove();
        }
        $this->render( 200, array('status'=>'success') );
    }
}