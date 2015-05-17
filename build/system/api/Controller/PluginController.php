<?php

namespace api\Controller;

class PluginController extends \api\Controller\JsonController
{


    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();
        $this->init_data('plugins');
    }

    public function init_data( $key='' )
    {
        parent::init_data( $key );


        foreach($this->data_array as $pl) {

            if( isset( $pl->installed ) && $pl->installed ) {
                $class= $pl->namespace.'\Plugin';
                if( !$this->app->container->has( $pl->namespace ) ) {
                    $this->app->add( new $class( $pl ) );
                }
            }
        }
    }


    public function find_plugins() {

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__ROOT__.'public/plugins')) as $filename)
        {
            if($filename->isFile() and $filename->isReadable() and '.' . $filename->getExtension() == EXT) {

                $nm = require_once $filename->getPathname();
                $ar = explode( '\\', $nm );
                $end = end( $ar );

                $defaults =  array(
                    'label' => $end,
                    'namespace' => $nm,
                    'installed' => 0,
                );

                $settings = $this->search( 'label', $end);
                if( count($settings) == 0 ) {
                    $this->data_array[] = (object) $defaults;
                }

            }

        }

    }


}