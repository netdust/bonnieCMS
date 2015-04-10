<?php

namespace api\Controller;

class MetaController extends \api\Controller\Controller
{

    public function get( )
    {
        $r = \Model::factory('Config')->find_many();
        foreach( $r as $item ){
            $n[$item->key] = $item->value;
        }

        $this->app->status( 200 );
        $this->render( $n );
    }

    public function put( $id )
    {
        $request = (array) json_decode($this->app->request()->getBody());
        $r = \cms\Config::save_data( $request );


        $this->app->status( 200 );
        $this->render( $request );
    }

}